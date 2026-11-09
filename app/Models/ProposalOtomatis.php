<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProposalOtomatis extends Model
{
    use HasFactory;

    protected $table = 'proposal_otomatis';

    protected $fillable = [
        'user_id', 'nama_kegiatan', 'jenis_proposal', 'latar_belakang', 'tujuan', 'sasaran',
        'indikator', 'luaran', 'dampak', 'penutup',
        'ttd_1_role', 'ttd_1_nama', 'ttd_1_jabatan', 'ttd_1_nim', 'ttd_1_file',
        'ttd_2_role', 'ttd_2_nama', 'ttd_2_jabatan', 'ttd_2_nim', 'ttd_2_file',
        'ttd_3_role', 'ttd_3_nama', 'ttd_3_jabatan', 'ttd_3_nim', 'ttd_3_file',
        'penandatangan',
        'status'
    ];

    protected $casts = [
        'penandatangan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tandaTanganDigitals(): MorphMany
    {
        return $this->morphMany(TandaTanganDigital::class, 'signable')->orderBy('signer_index');
    }

    /**
     * Daftar penandatangan yang sudah dinormalkan, dengan fallback ke kolom
     * ttd_1..ttd_3 lama bila data baru belum ada.
     */
    public function getPenandatanganListAttribute(): array
    {
        $list = $this->penandatangan ?? [];

        if (empty($list)) {
            foreach ([1, 2, 3] as $n) {
                $nama = $this->{"ttd_{$n}_nama"};
                if (! empty($nama)) {
                    $list[] = [
                        'jenis' => 'internal',
                        'role' => $this->{"ttd_{$n}_role"},
                        'nama' => $nama,
                        'jabatan' => $this->{"ttd_{$n}_jabatan"},
                        'nim' => $this->{"ttd_{$n}_nim"},
                    ];
                }
            }
        }

        return array_map(function ($p) {
            $role = $p['role'] ?? null;
            return [
                'jenis' => $p['jenis'] ?? 'internal',
                'role' => $role,
                'nama' => $p['nama'] ?? '-',
                'jabatan' => $p['jabatan'] ?? ($role ? ucfirst(str_replace('_', ' ', $role)) : 'Penandatangan'),
                'nim' => $p['nim'] ?? null,
            ];
        }, $list);
    }

    public function getSignaturesByIndexAttribute(): array
    {
        return $this->tandaTanganDigitals->keyBy('signer_index')->all();
    }

    public function rab()
    {
        return $this->hasMany(ProposalRab::class, 'proposal_id');
    }

    public function panitia()
    {
        return $this->hasMany(ProposalPanitia::class, 'proposal_id');
    }

    public function isKegiatan(): bool
    {
        return empty($this->jenis_proposal) || $this->jenis_proposal === 'kegiatan';
    }

    public function isPengadaanBarang(): bool
    {
        return $this->jenis_proposal === 'pengadaan_barang';
    }

    public function isPendanaanLomba(): bool
    {
        return $this->jenis_proposal === 'pendanaan_lomba';
    }

    public function getJenisProposalLabelAttribute(): string
    {
        return match ($this->jenis_proposal) {
            'pengadaan_barang' => 'Pengadaan Barang & Inventaris',
            'pendanaan_lomba' => 'Pendanaan Delegasi Lomba',
            default => 'Kegiatan Mahasiswa',
        };
    }

    public function getJudulDokumenAttribute(): string
    {
        return match ($this->jenis_proposal) {
            'pengadaan_barang' => 'PROPOSAL PENGAJUAN DANA PENGADAAN BARANG & INVENTARIS',
            'pendanaan_lomba' => 'PROPOSAL PENGAJUAN DANA DELEGASI LOMBA & KOMPETISI',
            default => 'PROPOSAL KEGIATAN MAHASISWA',
        };
    }

    public function getJenisProposalBadgeAttribute(): array
    {
        return match ($this->jenis_proposal) {
            'pengadaan_barang' => [
                'label' => 'Pengadaan Barang / Inventaris',
                'class' => 'bg-emerald-50 text-emerald-800 border-emerald-300 font-semibold',
                'icon' => '📦',
            ],
            'pendanaan_lomba' => [
                'label' => 'Pendanaan Lomba / Delegasi',
                'class' => 'bg-amber-50 text-amber-900 border-amber-300 font-semibold',
                'icon' => '🏆',
            ],
            default => [
                'label' => 'Kegiatan Mahasiswa',
                'class' => 'bg-blue-50 text-blue-800 border-blue-200 font-semibold',
                'icon' => '🎪',
            ],
        };
    }
}
