<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HimaFiturSetting;

class HimaFiturSettingSeeder extends Seeder
{
    /**
     * Seed data awal pengaturan fitur HIMA.
     * Semua fitur aktif secara default agar sama dengan fitur UKM,
     * dan dapat diatur / di-ceklis oleh BKHM.
     */
    public function run(): void
    {
        $fiturList = [
            [
                'fitur_key'   => 'pengajuan',
                'fitur_label' => 'Pengajuan Proposal & Dana',
                'deskripsi'   => 'Menu pengajuan kegiatan/dana baru dan melihat riwayat pengajuan kegiatan HIMA.',
                'is_aktif'    => true,
                'urutan'      => 1,
            ],
            [
                'fitur_key'   => 'proker',
                'fitur_label' => 'Program Kerja (Proker)',
                'deskripsi'   => 'Menu input, kelola, dan monitoring program kerja tahunan HIMA.',
                'is_aktif'    => true,
                'urutan'      => 2,
            ],
            [
                'fitur_key'   => 'sarpras',
                'fitur_label' => 'Sarpras (Peminjaman Fasilitas & Barang)',
                'deskripsi'   => 'Menu pengajuan dan riwayat peminjaman ruangan/tempat serta sarana/barang inventaris.',
                'is_aktif'    => true,
                'urutan'      => 3,
            ],
            [
                'fitur_key'   => 'persuratan',
                'fitur_label' => 'Persuratan Digital & Generator Dokumen',
                'deskripsi'   => 'Menu buat proposal digital, surat dinas/pengantar, LPJ otomatis, dan arsip dokumen digital.',
                'is_aktif'    => true,
                'urutan'      => 4,
            ],
            [
                'fitur_key'   => 'lpj',
                'fitur_label' => 'Laporan Pertanggungjawaban (LPJ)',
                'deskripsi'   => 'Menu monitoring pengunggahan dan evaluasi LPJ kegiatan HIMA.',
                'is_aktif'    => true,
                'urutan'      => 5,
            ],
            [
                'fitur_key'   => 'prestasi',
                'fitur_label' => 'Pelaporan Prestasi Mahasiswa',
                'deskripsi'   => 'Menu pencatatan dan pelaporan prestasi mahasiswa dari HIMA ke tingkat institusi.',
                'is_aktif'    => true,
                'urutan'      => 6,
            ],
            [
                'fitur_key'   => 'sp',
                'fitur_label' => 'Surat Peringatan (SP) Organisasi',
                'deskripsi'   => 'Menu melihat dan mengunduh surat peringatan resmi organisasi dari BKHM/BPM.',
                'is_aktif'    => true,
                'urutan'      => 7,
            ],
            [
                'fitur_key'   => 'informasi',
                'fitur_label' => 'Pusat Informasi & Jadwal Rapat',
                'deskripsi'   => 'Menu pusat berita, pengumuman publik, serta agenda/jadwal rapat koordinasi.',
                'is_aktif'    => true,
                'urutan'      => 8,
            ],
        ];

        foreach ($fiturList as $fitur) {
            HimaFiturSetting::updateOrCreate(
                ['fitur_key' => $fitur['fitur_key']],
                $fitur
            );
        }
    }
}
