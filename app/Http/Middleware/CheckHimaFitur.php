<?php

namespace App\Http\Middleware;

use App\Models\HimaFiturSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHimaFitur
{
    /**
     * Memeriksa apakah fitur yang diakses diizinkan untuk Himpunan Mahasiswa (HIMA).
     * Jika pengguna adalah akun HIMA dan fitur dinonaktifkan oleh BKHM (baik secara khusus untuk himpunan ini atau global),
     * redirect ke dashboard dengan pesan peringatan.
     * Pengguna non-HIMA (UKM, BEM, BPM, Admin, dll) tidak terpengaruh oleh pembatasan ini.
     */
    public function handle(Request $request, Closure $next, string $fitur): Response
    {
        $user = $request->user();

        if ($user && method_exists($user, 'isHima') && $user->isHima()) {
            if (!HimaFiturSetting::isAktif($fitur, $user)) {
                return redirect()->route('dashboard')
                    ->with('error', "Fitur '{$fitur}' saat ini dinonaktifkan oleh BKHM untuk organisasi {$user->name}.")
                    ->with('warning', "Fitur '{$fitur}' saat ini dinonaktifkan oleh BKHM untuk organisasi {$user->name}.");
            }
        }

        return $next($request);
    }
}
