<?php

namespace App\Http\Controllers\Satgas;

use App\Http\Controllers\Controller;
use App\Models\Konfigurasi;
use App\Models\SuratPeringatan;
use App\Models\TandaTanganDigital;
use App\Models\User;
use App\Services\DigitalSignatureService;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SatgasSpController extends Controller
{
    /**
     * Menampilkan daftar Surat Peringatan yang diterbitkan oleh Satgas PPKS.
     */
    public function index()
    {
        $query = SuratPeringatan::with(['target', 'creator', 'validator']);

        if (!Auth::user()->hasRole('admin')) {
            $query->where('created_by', Auth::id());
        }

        $suratPeringatans = $query->latest()->paginate(15);

        $counts = [
            'total' => SuratPeringatan::where('created_by', Auth::id())->count(),
            'disetujui' => SuratPeringatan::where('created_by', Auth::id())->where('status', 'disetujui')->count(),
            'proses' => SuratPeringatan::where('created_by', Auth::id())->where('status', 'menunggu_validasi')->count(),
            'ditolak' => SuratPeringatan::where('created_by', Auth::id())->where('status', 'ditolak')->count(),
        ];

        return view('satgas.sp.index', compact('suratPeringatans', 'counts'));
    }

    /**
     * Menampilkan form pembuatan Surat Peringatan baru oleh Satgas PPKS.
     */
    public function create()
    {
        $ormawas = User::role(['ormawa', 'bem', 'bpm'])->orderBy('name')->get();
        $prodis = [
            'S1 Teknik Informatika',
            'S1 Sistem Informasi',
            'S1 Teknik Sipil',
            'S1 Teknik Industri',
            'S1 Arsitektur',
        ];

        $konfig = Konfigurasi::pluck('nilai_konfigurasi', 'nama_konfigurasi');
        $wr3Info = [
            'nama' => ($konfig['wr3_nama'] ?? null) ?: 'Pejabat Wakil Rektor III',
            'nidn' => ($konfig['wr3_nidn'] ?? null) ?: '-',
            'jabatan' => $konfig['wr3_jabatan'] ?? 'Wakil Rektor III Bidang Kemahasiswaan dan Kerjasama',
        ];

        return view('satgas.sp.create', compact('ormawas', 'prodis', 'wr3Info'));
    }

    /**
     * Menyimpan dan meneruskan Surat Peringatan dari Satgas PPKS ke WR3 untuk pengesahan.
     */
    public function store(Request $request)
    {
        $request->merge(['tipe_sasaran' => $request->input('tipe_sasaran', 'mahasiswa')]);
        $tipe = $request->input('tipe_sasaran');

        $rules = [
            'tipe_sasaran' => 'required|in:ormawa,mahasiswa',
            'nomor_surat' => 'required|string|unique:surat_peringatans,nomor_surat',
            'tingkat' => 'required|in:SP-1,SP-2,SP-3',
            'perihal' => 'required|string|max:255',
            'alasan_singkat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'sanksi' => 'required|string',
            'tanggal_surat' => 'required|date',
        ];

        if ($tipe === 'mahasiswa') {
            $rules['target_mahasiswas'] = 'required|array|min:1';
            $rules['target_mahasiswas.*.nim'] = 'required|string|max:50';
            $rules['target_mahasiswas.*.nama'] = 'required|string|max:255';
            $rules['target_mahasiswas.*.prodi'] = 'required|string|max:255';
            $rules['target_mahasiswas.*.kontak'] = 'nullable|string|max:255';
        } else {
            $rules['target_user_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        $mahasiswas = [];
        if ($tipe === 'mahasiswa') {
            foreach ($request->input('target_mahasiswas', []) as $m) {
                $mahasiswas[] = [
                    'nim' => $m['nim'] ?? null,
                    'nama' => $m['nama'] ?? null,
                    'prodi' => $m['prodi'] ?? null,
                    'kontak' => $m['kontak'] ?? null,
                ];
            }
        }
        $pertama = $mahasiswas[0] ?? null;

        $konfig = Konfigurasi::pluck('nilai_konfigurasi', 'nama_konfigurasi');
        $pejabatNama = ($konfig['wr3_nama'] ?? null) ?: 'Pejabat Wakil Rektor III';
        $pejabatNidn = ($konfig['wr3_nidn'] ?? null) ?: '-';
        $pejabatJabatan = $konfig['wr3_jabatan'] ?? 'Wakil Rektor III Bidang Kemahasiswaan dan Kerjasama';

        $sp = SuratPeringatan::create([
            'tipe_sasaran' => $tipe,
            'target_user_id' => $tipe === 'mahasiswa' ? null : $request->target_user_id,
            'target_mahasiswas' => $tipe === 'mahasiswa' ? $mahasiswas : null,
            'target_nim' => $pertama['nim'] ?? null,
            'target_nama' => $pertama['nama'] ?? null,
            'target_prodi' => $pertama['prodi'] ?? null,
            'target_kontak' => $pertama['kontak'] ?? null,
            'nomor_surat' => $request->nomor_surat,
            'tingkat' => $request->tingkat,
            'status' => 'menunggu_validasi',
            'perihal' => $request->perihal,
            'alasan_singkat' => $request->alasan_singkat,
            'deskripsi' => $request->deskripsi,
            'sanksi' => $request->sanksi,
            'tanggal_surat' => $request->tanggal_surat,
            'penandatangan' => $pejabatNama,
            'pejabat_nama' => $pejabatNama,
            'pejabat_nidn' => $pejabatNidn,
            'pejabat_jabatan' => $pejabatJabatan,
            'is_internal_bpm' => false,
            'created_by' => Auth::id(),
        ]);

        // Notifikasi ke WR3 untuk pengesahan & BKHM sebagai arsip tembusan
        NotifikasiService::kirimKeRole(
            'wr3',
            'Draf Surat Peringatan ' . $sp->tingkat . ' (' . $sp->nomor_surat . ') dari Satgas PPKS untuk ' . $sp->nama_penerima . ' perihal "' . $sp->perihal . '" menunggu validasi dan pengesahan TTD digital Anda.'
        );

        NotifikasiService::kirimKeRole(
            'bkhm',
            'Pemberitahuan Tembusan: Satgas PPKS telah menerbitkan usulan Surat Peringatan ' . $sp->tingkat . ' (' . $sp->nomor_surat . ') untuk ' . $sp->nama_penerima . ' yang telah diteruskan ke Wakil Rektor III.'
        );

        return redirect()->route('satgas.sp.index')
            ->with('success', 'Surat Peringatan dari Satgas PPKS berhasil diajukan dan diteruskan ke Wakil Rektor III: ' . $sp->nomor_surat);
    }

    /**
     * Menampilkan detail Surat Peringatan terbitan Satgas PPKS.
     */
    public function show(SuratPeringatan $sp)
    {
        abort_unless(
            $sp->created_by === Auth::id() || Auth::user()->hasAnyRole(['admin', 'bkhm', 'wr3', 'satgas_ppks']),
            403
        );

        $sp->load(['target', 'creator', 'validator']);
        $konfig = Konfigurasi::pluck('nilai_konfigurasi', 'nama_konfigurasi');

        $signature = null;
        $qrCodeDataUri = null;
        if ($sp->isDisetujui()) {
            $signature = TandaTanganDigital::where('signable_type', get_class($sp))
                ->where('signable_id', $sp->id)
                ->latest()
                ->first();
            if ($signature) {
                $qrCodeDataUri = DigitalSignatureService::generateQrCodeDataUri($signature->verification_url, 120);
            }
        }

        return view('bkhm.sp_show', compact('sp', 'konfig', 'signature', 'qrCodeDataUri'));
    }
}

