<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('bpm') || $user->hasRole('admin') || $user->hasRole('bkhm')) {
            $prokers = ProgramKerja::with(['user', 'prodi'])->withCount('pengajuans')->latest()->paginate(10);
        } elseif ($user->hasRole('prodi')) {
            $prokers = ProgramKerja::with(['user', 'prodi'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where(function ($sub) use ($user) {
                        if ($user->kode_prodi) {
                            $sub->where('kode_prodi', $user->kode_prodi);
                        }
                        if ($user->prodi) {
                            $sub->orWhere('prodi', $user->prodi);
                        }
                    });
                })
                ->withCount('pengajuans')
                ->latest()
                ->paginate(10);
        } else {
            $prokers = ProgramKerja::with(['user', 'prodi'])->where('user_id', $user->id)->withCount('pengajuans')->latest()->paginate(10);
        }
        return view('proker.index', compact('prokers'));
    }

    public function create()
    {
        return view('proker.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proker' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'rencana_pelaksanaan' => 'required|date',
        ]);

        // FR-005: Validasi duplikasi proker per periode/tanggal untuk pengguna yang sama
        $exists = ProgramKerja::where('user_id', Auth::id())
            ->where('nama_proker', $request->nama_proker)
            ->where('rencana_pelaksanaan', $request->rencana_pelaksanaan)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Program kerja dengan nama dan tanggal pelaksanaan yang sama sudah terdaftar.');
        }

        $himaUser = Auth::user();
        $isHima = $himaUser->isHima() || $himaUser->kode_prodi || $himaUser->prodi;
        $statusProdi = $isHima ? 'menunggu' : 'tidak_perlu';

        ProgramKerja::create([
            'user_id' => Auth::id(),
            'nama_proker' => $request->nama_proker,
            'deskripsi' => $request->deskripsi,
            'rencana_pelaksanaan' => $request->rencana_pelaksanaan,
            'status' => 'rencana',
            'status_prodi' => $statusProdi,
        ]);

        // Notifikasi ke Program Studi jika pengusul adalah HIMA
        if ($isHima) {
            $prodiUsers = \App\Models\User::role('prodi')->where(function ($q) use ($himaUser) {
                if ($himaUser->kode_prodi) {
                    $q->where('kode_prodi', $himaUser->kode_prodi);
                }
                if ($himaUser->prodi) {
                    $q->orWhere('prodi', $himaUser->prodi);
                }
            })->get();

            $tgl = \Carbon\Carbon::parse($request->rencana_pelaksanaan)->isoFormat('D MMMM Y');
            foreach ($prodiUsers as $pUser) {
                \App\Services\NotifikasiService::kirim(
                    $pUser->id,
                    "Pemberitahuan Proker Baru: {$himaUser->name} telah mendaftarkan rencana program kerja '{$request->nama_proker}' untuk pelaksanaan tanggal {$tgl}. Mohon tinjau dan berikan persetujuan (ACC) pada menu Monitoring Proker."
                );
            }
        }

        return redirect()->route('proker.index')->with('success', 'Program kerja berhasil diajukan.');
    }

    public function update(Request $request, ProgramKerja $proker)
    {
        $request->validate([
            'status' => 'required|in:rencana,proses,terlaksana,kendala',
            'catatan_bpm' => 'nullable|string',
        ]);

        $proker->update([
            'status' => $request->status,
            'catatan_bpm' => $request->catatan_bpm,
        ]);

        return redirect()->route('proker.index')->with('success', 'Program kerja berhasil diperbarui.');
    }

    /**
     * Persetujuan (ACC) Program Kerja Himpunan oleh Program Studi (Kaprodi / Pembina).
     */
    public function accProdi(Request $request, ProgramKerja $proker)
    {
        $user = Auth::user();

        if (! $user->hasAnyRole(['prodi', 'admin'])) {
            abort(403, 'Akses ditolak: Hanya Program Studi atau Administrator yang berwenang memberikan persetujuan (ACC) proker.');
        }

        // Jika login sebagai prodi, pastikan proker berasal dari himpunan prodi yang sama
        if ($user->hasRole('prodi')) {
            $prokerUser = $proker->user;
            $matches = false;
            if ($user->kode_prodi && $prokerUser?->kode_prodi === $user->kode_prodi) {
                $matches = true;
            }
            if ($user->prodi && $prokerUser?->prodi === $user->prodi) {
                $matches = true;
            }
            if (! $matches) {
                abort(403, 'Anda hanya dapat memberikan persetujuan (ACC) pada program kerja Himpunan Mahasiswa di bawah Program Studi Anda.');
            }
        }

        $request->validate([
            'status_prodi' => 'required|in:disetujui,ditolak,menunggu',
            'catatan_prodi' => 'nullable|string|max:1000',
        ]);

        $status = $request->status_prodi;
        $proker->update([
            'status_prodi'       => $status,
            'catatan_prodi'      => $request->catatan_prodi,
            'prodi_id'           => $user->id,
            'disetujui_prodi_at' => $status === 'disetujui' ? now() : null,
        ]);

        $prodiName = $user->prodi ?: $user->name;
        if ($status === 'disetujui') {
            \App\Services\NotifikasiService::kirim(
                $proker->user_id,
                "Program Kerja Di-ACC: Program Studi {$prodiName} telah MENYETUJUI (ACC) program kerja '{$proker->nama_proker}'. Anda sekarang dapat menautkan proker ini saat mengajukan proposal kegiatan."
            );
            $msg = "Program kerja '{$proker->nama_proker}' berhasil disetujui (ACC).";
        } elseif ($status === 'ditolak') {
            $catatanText = $request->catatan_prodi ? ": \"{$request->catatan_prodi}\"" : ".";
            \App\Services\NotifikasiService::kirim(
                $proker->user_id,
                "Catatan Revisi Proker: Program Studi {$prodiName} memberikan catatan revisi pada program kerja '{$proker->nama_proker}'{$catatanText}"
            );
            $msg = "Catatan revisi untuk program kerja '{$proker->nama_proker}' berhasil disimpan.";
        } else {
            $msg = "Status persetujuan prodi berhasil dikembalikan ke status menunggu.";
        }

        return redirect()->route('proker.index')->with('success', $msg);
    }
}
