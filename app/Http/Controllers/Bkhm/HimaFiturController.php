<?php

namespace App\Http\Controllers\Bkhm;

use App\Http\Controllers\Controller;
use App\Models\HimaFiturSetting;
use App\Models\User;
use Illuminate\Http\Request;

class HimaFiturController extends Controller
{
    /**
     * Halaman pengaturan fitur HIMA (hanya BKHM/Admin).
     * Mendukung pengaturan Global (semua HIMA) dan Spesifik per-Himpunan.
     */
    public function index(Request $request)
    {
        $target = $request->query('target', 'all');

        // Ambil semua organisasi HIMA yang ada di sistem
        $himaList = User::all()
            ->filter(fn($u) => method_exists($u, 'isHima') && $u->isHima())
            ->values();

        $selectedHima = null;
        if ($target !== 'all') {
            $selectedHima = $himaList->firstWhere('id', (int)$target);
            if (!$selectedHima) {
                return redirect()->route('bkhm.hima-fitur.index', ['target' => 'all'])
                    ->with('warning', 'Himpunan yang dipilih tidak ditemukan.');
            }
        }

        // Ambil daftar master fitur
        $masterFitur = HimaFiturSetting::orderBy('urutan')->get();

        // Tentukan status aktif untuk target yang sedang dilihat
        $fiturList = $masterFitur->map(function ($fitur) use ($selectedHima) {
            $isAktif = $selectedHima
                ? HimaFiturSetting::isAktif($fitur->fitur_key, $selectedHima)
                : (bool)$fitur->is_aktif;

            return (object) [
                'fitur_key'   => $fitur->fitur_key,
                'fitur_label' => $fitur->fitur_label,
                'deskripsi'   => $fitur->deskripsi,
                'urutan'      => $fitur->urutan,
                'is_aktif'    => $isAktif,
            ];
        });

        $hasCustom = $selectedHima ? HimaFiturSetting::hasCustomSettings($selectedHima->id) : false;

        // Ambil ringkasan kustomisasi per HIMA untuk ditampilkan di kartu tab
        $himaSummary = $himaList->map(function ($hima) use ($masterFitur) {
            $isCustom = HimaFiturSetting::hasCustomSettings($hima->id);
            $activeCount = 0;
            foreach ($masterFitur as $f) {
                if (HimaFiturSetting::isAktif($f->fitur_key, $hima)) {
                    $activeCount++;
                }
            }
            return [
                'user'         => $hima,
                'has_custom'   => $isCustom,
                'active_count' => $activeCount,
                'total_count'  => $masterFitur->count(),
            ];
        });

        return view('bkhm.hima-fitur.index', compact(
            'fiturList',
            'himaList',
            'target',
            'selectedHima',
            'hasCustom',
            'himaSummary',
            'masterFitur'
        ));
    }

    /**
     * Simpan perubahan toggle fitur dari checkbox BKHM (Global atau per-Himpunan).
     */
    public function update(Request $request)
    {
        $request->validate([
            'target'        => 'nullable|string',
            'aktif_fitur'   => 'nullable|array',
            'aktif_fitur.*' => 'string|exists:hima_fitur_settings,fitur_key',
        ]);

        $target = $request->input('target', 'all');
        $aktifKeys = $request->input('aktif_fitur', []);

        if ($target === 'all') {
            // Update global default settings
            $semuaFitur = HimaFiturSetting::all();
            foreach ($semuaFitur as $fitur) {
                $fitur->update(['is_aktif' => in_array($fitur->fitur_key, $aktifKeys)]);
            }
            HimaFiturSetting::clearCache();

            return redirect()
                ->route('bkhm.hima-fitur.index', ['target' => 'all'])
                ->with('success', 'Pengaturan fitur default (berlaku untuk semua Himpunan) berhasil disimpan.');
        }

        // Target spesifik HIMA
        $hima = User::findOrFail((int)$target);
        if (!$hima->isHima()) {
            return redirect()
                ->route('bkhm.hima-fitur.index')
                ->with('warning', 'User yang dipilih bukan organisasi Himpunan Mahasiswa.');
        }

        HimaFiturSetting::setAktifForUser($hima->id, $aktifKeys);

        return redirect()
            ->route('bkhm.hima-fitur.index', ['target' => $hima->id])
            ->with('success', "Pengaturan fitur khusus untuk {$hima->name} berhasil disimpan.");
    }

    /**
     * Kembalikan pengaturan HIMA spesifik ke pengaturan default global.
     */
    public function resetUser(User $user)
    {
        if (!$user->isHima()) {
            return redirect()
                ->route('bkhm.hima-fitur.index')
                ->with('warning', 'User yang dipilih bukan organisasi Himpunan Mahasiswa.');
        }

        HimaFiturSetting::resetUserToDefault($user->id);

        return redirect()
            ->route('bkhm.hima-fitur.index', ['target' => $user->id])
            ->with('success', "Pengaturan fitur untuk {$user->name} berhasil dikembalikan ke default global.");
    }
}
