<?php

namespace App\Http\Controllers\Bpm;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\SaldoHistori;
use App\Models\User;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    /**
     * Tampilkan rekapitulasi anggaran seluruh organisasi mahasiswa ITG
     * (HIMA, UKM, BEM, dan BPM) untuk fungsi pengawasan BPM.
     */
    public function index(Request $request)
    {
        $kategori = $request->query('kategori', 'all');
        $search   = trim((string) $request->query('q', ''));

        // Query organisasi yang memiliki saldo / pagu anggaran
        $baseQuery = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['ormawa', 'bem', 'bpm']);
        });

        // Hitung total ringkasan global (semua ormawa tanpa terpengaruh filter pencarian lokal)
        $allUsers = (clone $baseQuery)->get();
        $totalPaguAll = $allUsers->sum(fn ($u) => (float) ($u->saldo_awal ?? 0));
        $totalSisaAll = $allUsers->sum(fn ($u) => (float) ($u->saldo ?? 0));
        $totalTerpakaiAll = max(0, $totalPaguAll - $totalSisaAll);
        $persenSerapanAll = $totalPaguAll > 0 ? round(($totalTerpakaiAll / $totalPaguAll) * 100, 1) : 0;

        $counts = [
            'total' => $allUsers->count(),
            'hima'  => $allUsers->filter(fn ($u) => $u->hasRole('ormawa') && ($u->jenis_ormawa === 'hima' || !empty($u->prodi)))->count(),
            'ukm'   => $allUsers->filter(fn ($u) => $u->hasRole('ormawa') && $u->jenis_ormawa === 'ukm')->count(),
            'bem'   => $allUsers->filter(fn ($u) => $u->hasRole('bem'))->count(),
            'bpm'   => $allUsers->filter(fn ($u) => $u->hasRole('bpm'))->count(),
        ];

        // Terapkan filter kategori tab
        $query = clone $baseQuery;
        if ($kategori === 'hima') {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'ormawa'))
                ->where(function ($q) {
                    $q->where('jenis_ormawa', 'hima')->orWhereNotNull('prodi');
                });
        } elseif ($kategori === 'ukm') {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'ormawa'))
                ->where('jenis_ormawa', 'ukm');
        } elseif ($kategori === 'bem') {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'bem'));
        } elseif ($kategori === 'bpm') {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'bpm'));
        }

        // Terapkan filter pencarian nama organisasi / prodi / ketua
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prodi', 'like', "%{$search}%")
                    ->orWhere('nama_ketua', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $organisasiList = $query->with(['roles', 'pengajuans.dana'])
            ->get()
            ->map(function ($u) {
                $saldoAwal = (float) ($u->saldo_awal ?? 0);
                $saldoSisa = (float) ($u->saldo ?? 0);
                $terpakai = max(0, $saldoAwal - $saldoSisa);
                $persen = $saldoAwal > 0 ? round(($terpakai / $saldoAwal) * 100, 1) : 0;

                $totalPengajuan = $u->pengajuans->count();
                $totalCair = (float) $u->pengajuans->sum(fn ($p) => (float) ($p->dana?->nominal_cair ?? 0));

                $tipeLabel = 'Ormawa';
                if ($u->hasRole('bem')) {
                    $tipeLabel = 'BEM';
                } elseif ($u->hasRole('bpm')) {
                    $tipeLabel = 'BPM';
                } elseif ($u->jenis_ormawa === 'hima' || !empty($u->prodi)) {
                    $tipeLabel = 'HIMA';
                } elseif ($u->jenis_ormawa === 'ukm') {
                    $tipeLabel = 'UKM';
                }

                return [
                    'id'               => $u->id,
                    'user'             => $u,
                    'name'             => $u->name,
                    'tipe'             => $tipeLabel,
                    'prodi'            => $u->prodi,
                    'nama_ketua'       => $u->nama_ketua,
                    'kontak_ketua'     => $u->kontak_ketua ?? $u->email,
                    'saldo_awal'       => $saldoAwal,
                    'saldo'            => $saldoSisa,
                    'terpakai'         => $terpakai,
                    'persen'           => $persen,
                    'total_pengajuan'  => $totalPengajuan,
                    'total_cair'       => $totalCair,
                ];
            });

        return view('bpm.anggaran.index', compact(
            'organisasiList',
            'totalPaguAll',
            'totalTerpakaiAll',
            'totalSisaAll',
            'persenSerapanAll',
            'counts',
            'kategori',
            'search'
        ));
    }

    /**
     * Tampilkan detail anggaran dan histori pengajuan dana satu organisasi.
     */
    public function show(User $user)
    {
        // Pastikan user adalah organisasi mahasiswa (ormawa, bem, bpm)
        if (! $user->hasAnyRole(['ormawa', 'bem', 'bpm'])) {
            return redirect()->route('bpm.anggaran.index')
                ->with('error', 'Pengguna yang dipilih bukan entitas organisasi kemahasiswaan.');
        }

        $saldoAwal = (float) ($user->saldo_awal ?? 0);
        $saldoSisa = (float) ($user->saldo ?? 0);
        $terpakai = max(0, $saldoAwal - $saldoSisa);
        $persen = $saldoAwal > 0 ? round(($terpakai / $saldoAwal) * 100, 1) : 0;

        // Daftar pengajuan proposal & pencairan dana
        $pengajuans = $user->pengajuans()
            ->with(['state', 'dana'])
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        // Histori mutasi saldo kas ormawa
        $saldoHistori = $user->saldoHistori()
            ->with('actor')
            ->latest()
            ->take(20)
            ->get();

        $tipeLabel = 'Ormawa';
        if ($user->hasRole('bem')) {
            $tipeLabel = 'BEM';
        } elseif ($user->hasRole('bpm')) {
            $tipeLabel = 'BPM';
        } elseif ($user->jenis_ormawa === 'hima' || !empty($user->prodi)) {
            $tipeLabel = 'HIMA';
        } elseif ($user->jenis_ormawa === 'ukm') {
            $tipeLabel = 'UKM';
        }

        return view('bpm.anggaran.show', compact(
            'user',
            'tipeLabel',
            'saldoAwal',
            'saldoSisa',
            'terpakai',
            'persen',
            'pengajuans',
            'saldoHistori'
        ));
    }
}
