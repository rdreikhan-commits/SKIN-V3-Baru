<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_proker',
        'deskripsi',
        'rencana_pelaksanaan',
        'status',
        'status_prodi',
        'catatan_bpm',
        'catatan_prodi',
        'prodi_id',
        'disetujui_prodi_at',
    ];

    protected $casts = [
        'rencana_pelaksanaan' => 'date',
        'disetujui_prodi_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prodi_id');
    }

    /**
     * Apakah program kerja ini berasal dari Himpunan Mahasiswa (di bawah Prodi).
     */
    public function isHimaProker(): bool
    {
        return (bool) ($this->user?->isHima() || $this->user?->kode_prodi || $this->user?->prodi);
    }

    /**
     * Apakah program kerja ini sudah di-ACC oleh Prodi (atau memang non-HIMA).
     */
    public function isApprovedByProdi(): bool
    {
        if (! $this->isHimaProker()) {
            return true;
        }

        return $this->status_prodi === 'disetujui';
    }

    public function getStatusProdiLabelAttribute(): string
    {
        return match ($this->status_prodi) {
            'disetujui'   => 'Disetujui Prodi (ACC)',
            'menunggu'    => 'Menunggu ACC Prodi',
            'ditolak'     => 'Revisi / Ditolak Prodi',
            default       => 'Tidak Perlu (Non-HIMA)',
        };
    }

    public function getStatusProdiBadgeClassAttribute(): string
    {
        return match ($this->status_prodi) {
            'disetujui'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'menunggu'    => 'bg-amber-50 text-amber-700 border-amber-200',
            'ditolak'     => 'bg-rose-50 text-rose-700 border-rose-200',
            default       => 'bg-slate-50 text-slate-500 border-slate-200',
        };
    }

    /**
     * Proposal kegiatan yang terhubung dengan program kerja ini.
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'program_kerja_id');
    }
}
