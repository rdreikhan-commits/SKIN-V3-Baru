<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LpjMultiTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected User $hima;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'ormawa']);
        Role::firstOrCreate(['name' => 'admin']);

        $this->hima = User::factory()->create([
            'name' => 'HIMA Informatika',
            'username' => 'hima_if',
            'email' => 'hima_if@itg.ac.id',
            'jenis_ormawa' => 'hima',
            'nama_ketua' => 'Muhammad Rasyid Ridha',
            'nim_ketua' => '2206001',
            'nama_sekretaris' => 'Aisyah Putri Azzahra',
            'nim_sekretaris' => '2206002',
        ]);
        $this->hima->assignRole('ormawa');
    }

    public function test_create_lpj_page_displays_all_three_template_options(): void
    {
        $response = $this->actingAs($this->hima)->get(route('generator.lpj.create'));

        $response->assertOk();
        $response->assertSee('LPJ Kegiatan Mahasiswa');
        $response->assertSee('LPJ Pengadaan Barang');
        $response->assertSee('LPJ Delegasi Perlombaan');
    }

    public function test_create_lpj_with_query_param_sets_default_template(): void
    {
        $responseBarang = $this->actingAs($this->hima)->get(route('generator.lpj.create', ['jenis_lpj' => 'pengadaan_barang']));
        $responseBarang->assertOk();
        $responseBarang->assertSee("jenisLpj: 'pengadaan_barang'", false);

        $responseLomba = $this->actingAs($this->hima)->get(route('generator.lpj.create', ['jenis_lpj' => 'pendanaan_lomba']));
        $responseLomba->assertOk();
        $responseLomba->assertSee("jenisLpj: 'pendanaan_lomba'", false);
    }

    public function test_store_and_view_lpj_pengadaan_barang(): void
    {
        $payload = [
            'action' => 'print',
            'jenis_lpj' => 'pengadaan_barang',
            'nama_kegiatan' => 'Pengadaan Multimedia & Proyektor Sekretariat HIMA',
            'tahun_akademik' => '2026-2027',
            'waktu_hari_tanggal' => '10 November 2026',
            'lokasi_penempatan' => 'Ruang Sekretariat HIMA Gedung D Lantai 2',
            'penanggung_jawab_aset' => 'Divisi Rumah Tangga & Sarpras HIMA',
            'urgensi_pengadaan' => 'Kebutuhan mendesak untuk proyektor dan perlengkapan inventaris demi rapat dan workshop.',
            'pemanfaatan_aset' => 'Digunakan untuk operasional harian dan peminjaman divisi kerja.',
            'bast_klausul' => 'Barang telah diterima dalam kondisi baru 100% dan berfungsi normal.',
            'sop_pemeliharaan' => 'Inventaris dirawat berkala dan dicatat dalam buku peminjaman aset.',
            'barang_items' => [
                [
                    'nama' => 'Proyektor Mini Portable',
                    'spesifikasi' => 'Epson EB-E500 3300 Lumens',
                    'jumlah' => '1 Unit',
                    'kondisi' => 'Baik / Baru 100%',
                    'lokasi' => 'Sekretariat Gedung D',
                ],
                [
                    'nama' => 'Mic Wireless Dual Channel',
                    'spesifikasi' => 'Boya BY-M1V2',
                    'jumlah' => '1 Set',
                    'kondisi' => 'Normal',
                    'lokasi' => 'Lemari Multimedia',
                ],
            ],
            'keuangan_items' => [
                [
                    'tanggal' => '10 Nov 2026',
                    'kebutuhan' => 'Dana Kemahasiswaan ITG',
                    'pemasukan' => 4500000,
                    'pengeluaran' => 0,
                    'sisa' => 4500000,
                    'keterangan' => 'Pencairan Kas',
                ],
                [
                    'tanggal' => '11 Nov 2026',
                    'kebutuhan' => 'Beli Proyektor & Mic Wireless',
                    'pemasukan' => 0,
                    'pengeluaran' => 4200000,
                    'sisa' => 300000,
                    'keterangan' => 'Nota Toko Elektronik No. 881',
                ],
            ],
            'penandatangan_jenis' => ['internal', 'internal'],
            'penandatangan_role' => ['ormawa', 'pelaksana'],
            'penandatangan_nama' => ['Muhammad Rasyid Ridha', 'Fajar Nugraha'],
            'penandatangan_jabatan' => ['Ketua HIMA', 'Penanggung Jawab Inventaris'],
            'penandatangan_nim' => ['2206001', '2206005'],
        ];

        $response = $this->actingAs($this->hima)->post(route('generator.lpj.store'), $payload);
        $response->assertSessionHasNoErrors();

        $lpj = Letter::where('type', 'lpj')
            ->where('perihal', 'like', '%Pengadaan Multimedia%')
            ->first();

        $this->assertNotNull($lpj);
        $this->assertEquals('pengadaan_barang', $lpj->metadata['jenis_lpj']);
        $this->assertCount(2, $lpj->metadata['barang_items']);
        $this->assertEquals('Proyektor Mini Portable', $lpj->metadata['barang_items'][0]['nama']);

        // Check view show preview
        $showResponse = $this->actingAs($this->hima)->get(route('generator.lpj.show', $lpj));
        $showResponse->assertOk();
        $showResponse->assertSee('LAPORAN PERTANGGUNGJAWABAN PENGADAAN BARANG &amp; INVENTARIS', false);
        $showResponse->assertSee('Epson EB-E500 3300 Lumens');
        $showResponse->assertSee('Berita Acara Penerimaan Barang (BAST)');
        $showResponse->assertSee('Penanggung Jawab Inventaris');
        // Ensure event audience attendance table is NOT rendered
        $showResponse->assertDontSee('DAFTAR HADIR PENGADAAN');
    }

    public function test_store_and_view_lpj_pendanaan_lomba(): void
    {
        $payload = [
            'action' => 'print',
            'jenis_lpj' => 'pendanaan_lomba',
            'nama_kegiatan' => 'Delegasi Kompetisi Gemastik XIX Divisi Programming',
            'tahun_akademik' => '2026-2027',
            'waktu_hari_tanggal' => '14 – 17 Oktober 2026',
            'tempat' => 'Kampus Universitas Indonesia Depok',
            'penyelenggara_lomba' => 'Pusat Prestasi Nasional (Puspresnas)',
            'tingkat_kejuaraan' => 'Nasional',
            'dosen_pendamping' => 'Ir. Muhammad Fauzi, M.T.',
            'capaian_prestasi' => "JUARA II (MEDALI PERAK) - Kategori Programming Competition\nSkor: 94.50",
            'tahapan_seleksi' => 'Tahap 1: Seleksi Berkas, Tahap 2: Babak Penyisihan, Tahap 3: Babak Final',
            'evaluasi_lomba' => 'Delegasi tampil percaya diri dan memecahkan 8 dari 10 soal algoritma dengan optimal.',
            'delegasi_items' => [
                [
                    'nama' => 'Muhammad Rasyid Ridha',
                    'nim' => '2206001',
                    'prodi' => 'Informatika',
                    'peran' => 'Ketua Tim Delegasi',
                ],
                [
                    'nama' => 'Aisyah Putri Azzahra',
                    'nim' => '2206002',
                    'prodi' => 'Informatika',
                    'peran' => 'Anggota 1',
                ],
            ],
            'keuangan_items' => [
                [
                    'tanggal' => '12 Okt 2026',
                    'kebutuhan' => 'Dana Bantuan Delegasi ITG',
                    'pemasukan' => 3000000,
                    'pengeluaran' => 0,
                    'sisa' => 3000000,
                    'keterangan' => 'Transfer Bank',
                ],
                [
                    'tanggal' => '14 Okt 2026',
                    'kebutuhan' => 'Registrasi & Transportasi Kereta',
                    'pemasukan' => 0,
                    'pengeluaran' => 2800000,
                    'sisa' => 200000,
                    'keterangan' => 'Tiket KAI & Bukti Registrasi',
                ],
            ],
            'penandatangan_jenis' => ['internal', 'internal'],
            'penandatangan_role' => ['ormawa', 'pelaksana'],
            'penandatangan_nama' => ['Muhammad Rasyid Ridha', 'Muhammad Rasyid Ridha'],
            'penandatangan_jabatan' => ['Ketua HIMA', 'Ketua Tim Delegasi'],
            'penandatangan_nim' => ['2206001', '2206001'],
        ];

        $response = $this->actingAs($this->hima)->post(route('generator.lpj.store'), $payload);
        $response->assertSessionHasNoErrors();

        $lpj = Letter::where('type', 'lpj')
            ->where('perihal', 'like', '%Delegasi Kompetisi Gemastik%')
            ->first();

        $this->assertNotNull($lpj);
        $this->assertEquals('pendanaan_lomba', $lpj->metadata['jenis_lpj']);
        $this->assertCount(2, $lpj->metadata['delegasi_items']);
        $this->assertEquals('Ketua Tim Delegasi', $lpj->metadata['delegasi_items'][0]['peran']);

        // Check view show preview
        $showResponse = $this->actingAs($this->hima)->get(route('generator.lpj.show', $lpj));
        $showResponse->assertOk();
        $showResponse->assertSee('LAPORAN PERTANGGUNGJAWABAN DELEGASI PERLOMBAAN MAHASISWA', false);
        $showResponse->assertSee('MEDALI PERAK');
        $showResponse->assertSee('Pusat Prestasi Nasional (Puspresnas)');
        $showResponse->assertSee('Ketua Tim Delegasi');
        // Ensure event audience attendance table is NOT rendered
        $showResponse->assertDontSee('DAFTAR HADIR DELEGASI');
    }

    public function test_download_pdf_lpj_pengadaan_barang(): void
    {
        $lpj = Letter::create([
            'user_id' => $this->hima->id,
            'type' => 'lpj',
            'perihal' => 'Laporan Pertanggungjawaban (LPJ) Pengadaan Barang - Beli Kamera',
            'content' => json_encode([
                'jenis_lpj' => 'pengadaan_barang',
                'tahun_akademik' => '2026-2027',
                'waktu_hari_tanggal' => '10 Nov 2026',
                'lokasi_penempatan' => 'Sekretariat HIMA',
                'urgensi_pengadaan' => 'Urgensi aset multimedia',
                'pemanfaatan_aset' => 'Dokumentasi kegiatan',
                'bast_klausul' => 'Diterima dalam kondisi baik',
                'sop_pemeliharaan' => 'Disimpan di lemari',
            ]),
            'metadata' => [
                'jenis_lpj' => 'pengadaan_barang',
                'barang_items' => [
                    [
                        'nama' => 'Kamera DSLR Canon',
                        'spesifikasi' => 'EOS 3000D',
                        'jumlah' => '1 Unit',
                        'kondisi' => '100% Baru',
                        'lokasi' => 'Sekretariat',
                    ]
                ],
                'keuangan_items' => [
                    [
                        'tanggal' => '10 Nov 2026',
                        'kebutuhan' => 'Beli Kamera',
                        'pemasukan' => 5000000,
                        'pengeluaran' => 4800000,
                        'sisa' => 200000,
                        'keterangan' => 'Lunas',
                    ]
                ],
            ],
        ]);

        $response = $this->actingAs($this->hima)->get(route('generator.lpj.pdf', $lpj));
        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_download_pdf_lpj_pendanaan_lomba(): void
    {
        $lpj = Letter::create([
            'user_id' => $this->hima->id,
            'type' => 'lpj',
            'perihal' => 'Laporan Pertanggungjawaban (LPJ) Delegasi Perlombaan - Lomba Web IT',
            'content' => json_encode([
                'jenis_lpj' => 'pendanaan_lomba',
                'tahun_akademik' => '2026-2027',
                'penyelenggara_lomba' => 'Universitas Gadjah Mada',
                'tingkat_kejuaraan' => 'Nasional',
                'waktu_hari_tanggal' => '20 Nov 2026',
                'tempat' => 'Yogyakarta',
                'dosen_pendamping' => 'Bpk Dosen, M.Kom.',
                'capaian_prestasi' => 'Juara 1 Web Design',
            ]),
            'metadata' => [
                'jenis_lpj' => 'pendanaan_lomba',
                'delegasi_items' => [
                    [
                        'nama' => 'Budi Santoso',
                        'nim' => '2206010',
                        'prodi' => 'Informatika',
                        'peran' => 'Ketua Delegasi',
                    ]
                ],
                'keuangan_items' => [
                    [
                        'tanggal' => '19 Nov 2026',
                        'kebutuhan' => 'Tiket dan Registrasi',
                        'pemasukan' => 2000000,
                        'pengeluaran' => 1900000,
                        'sisa' => 100000,
                        'keterangan' => 'Struk Terlampir',
                    ]
                ],
            ],
        ]);

        $response = $this->actingAs($this->hima)->get(route('generator.lpj.pdf', $lpj));
        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
