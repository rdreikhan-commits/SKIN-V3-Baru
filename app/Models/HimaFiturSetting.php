<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HimaFiturSetting extends Model
{
    use HasFactory;

    protected $table = 'hima_fitur_settings';

    protected $fillable = [
        'fitur_key',
        'fitur_label',
        'deskripsi',
        'is_aktif',
        'urutan',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan'   => 'integer',
    ];

    /**
     * Ambil status aktif sebuah fitur berdasarkan key-nya dan user (HIMA) terkait.
     * Jika user memiliki pengaturan custom di tabel hima_ormawa_fitur_settings, gunakan pengaturan tersebut.
     * Jika belum/tidak dikustomisasi, fallback ke pengaturan global default.
     *
     * @param string $key
     * @param \App\Models\User|int|null $user
     * @return bool
     */
    public static function isAktif(string $key, $user = null): bool
    {
        if ($user === null && Auth::check()) {
            $user = Auth::user();
        }

        if ($user) {
            $userId = is_numeric($user) ? (int)$user : $user->id;
            $userCustom = static::getUserFiturCached($userId);
            if ($userCustom !== null && array_key_exists($key, $userCustom)) {
                return (bool)$userCustom[$key];
            }
        }

        $settings = static::getAllCached();
        return isset($settings[$key]) ? (bool)$settings[$key] : true; // default aktif bila belum ada di DB
    }

    /**
     * Ambil semua pengaturan fitur global dalam format ['key' => is_aktif].
     * Digunakan di sidebar dan middleware.
     */
    public static function getAllCached(): array
    {
        return Cache::remember('hima_fitur_settings', 3600, function () {
            return static::all()->pluck('is_aktif', 'fitur_key')->toArray();
        });
    }

    /**
     * Ambil pengaturan fitur khusus untuk user tertentu (HIMA).
     * Mengembalikan array ['fitur_key' => is_aktif] jika memiliki pengaturan custom,
     * atau null jika masih mengikuti default global.
     */
    public static function getUserFiturCached(int $userId): ?array
    {
        return Cache::remember("hima_user_fitur_{$userId}", 3600, function () use ($userId) {
            $records = HimaOrmawaFiturSetting::where('user_id', $userId)->get();
            if ($records->isEmpty()) {
                return null;
            }
            return $records->pluck('is_aktif', 'fitur_key')->toArray();
        });
    }

    /**
     * Apakah user HIMA ini memiliki pengaturan fitur kustom.
     */
    public static function hasCustomSettings(int $userId): bool
    {
        return static::getUserFiturCached($userId) !== null;
    }

    /**
     * Simpan pengaturan fitur khusus untuk user tertentu (HIMA).
     */
    public static function setAktifForUser(int $userId, array $aktifKeys): void
    {
        $allKeys = static::pluck('fitur_key')->toArray();
        if (empty($allKeys)) {
            $allKeys = $aktifKeys;
        }

        foreach ($allKeys as $key) {
            HimaOrmawaFiturSetting::updateOrCreate(
                ['user_id' => $userId, 'fitur_key' => $key],
                ['is_aktif' => in_array($key, $aktifKeys)]
            );
        }
        static::clearUserCache($userId);
    }

    /**
     * Kembalikan pengaturan HIMA tertentu ke default global.
     */
    public static function resetUserToDefault(int $userId): void
    {
        HimaOrmawaFiturSetting::where('user_id', $userId)->delete();
        static::clearUserCache($userId);
    }

    /**
     * Set status aktif sebuah fitur global dan refresh cache.
     */
    public static function setAktif(string $key, bool $aktif, ?string $label = null): void
    {
        $setting = static::firstOrNew(['fitur_key' => $key]);
        $setting->is_aktif = $aktif;
        if (!$setting->exists) {
            $setting->fitur_label = $label ?? ucfirst($key);
        } elseif ($label !== null) {
            $setting->fitur_label = $label;
        }
        $setting->save();
        static::clearCache();
    }

    /**
     * Hapus cache pengaturan global dan seluruh user cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('hima_fitur_settings');
    }

    /**
     * Hapus cache khusus user tertentu.
     */
    public static function clearUserCache(int $userId): void
    {
        Cache::forget("hima_user_fitur_{$userId}");
    }

    /**
     * Scope: fitur yang aktif, diurutkan.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true)->orderBy('urutan');
    }
}
