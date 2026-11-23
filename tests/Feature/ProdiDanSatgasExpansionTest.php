<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\Notifikasi;
use App\Models\ProgramKerja;
use App\Models\SuratPeringatan;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdiDanSatgasExpansionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_cannot_create_prodi_account_since_prodi_is_removed()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Program Studi Teknik Sipil',
            'email' => 'prodi.ts@itg.ac.id',
            'username' => 'prodi_ts',
            'password' => 'Password123#',
            'role' => 'prodi',
            'prodi' => 'Teknik Sipil',
            'kode_prodi' => 'TS',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', [
            'username' => 'prodi_ts',
        ]);
    }

    public function test_admin_can_create_satgas_ppks_account()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Satgas PPKS ITG 2026',
            'email' => 'satgas.ppks26@itg.ac.id',
            'username' => 'satgas_ppks26',
            'password' => 'Password123#',
            'role' => 'satgas_ppks',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $user = User::where('username', 'satgas_ppks26')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('satgas_ppks'));
    }

    public function test_satgas_ppks_can_issue_sp_and_forward_to_wr3()
    {
        $satgas = User::factory()->create(['name' => 'Satgas PPKS ITG']);
        $satgas->assignRole('satgas_ppks');

        $wr3 = User::factory()->create(['name' => 'Wakil Rektor III']);
        $wr3->assignRole('wr3');

        $response = $this->actingAs($satgas)->post(route('satgas.sp.store'), [
            'tipe_sasaran' => 'mahasiswa',
            'target_mahasiswas' => [
                [
                    'nim' => '2306099',
                    'nama' => 'Oknum Mahasiswa',
                    'prodi' => 'S1 Teknik Informatika',
                    'kontak' => '08123456789',
                ]
            ],
            'nomor_surat' => 'SP/PPKS/2026/001',
            'tingkat' => 'SP-1',
            'perihal' => 'Peringatan Pelanggaran Norma PPKS',
            'alasan_singkat' => 'Pelanggaran etika dan pelecehan verbal',
            'deskripsi' => 'Berdasarkan laporan saksi dan bukti investigasi satgas...',
            'sanksi' => 'Teguran tertulis dan skorsing sementara dari kegiatan ormawa.',
            'tanggal_surat' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('satgas.sp.index'));

        $sp = SuratPeringatan::where('nomor_surat', 'SP/PPKS/2026/001')->first();
        $this->assertNotNull($sp);
        $this->assertEquals('menunggu_validasi', $sp->status);
        $this->assertEquals('Satgas PPKS', $sp->penerbit_label);
        $this->assertEquals($satgas->id, $sp->created_by);

        // WR3 menerima notifikasi validasi
        $notifWr3 = Notifikasi::where('user_id', $wr3->id)->first();
        $this->assertNotNull($notifWr3);
        $this->assertStringContainsString('Satgas PPKS', $notifWr3->pesan);
    }

    public function test_satgas_ppks_can_issue_sp_for_ormawa_and_forward_to_wr3()
    {
        $satgas = User::factory()->create(['name' => 'Satgas PPKS ITG']);
        $satgas->assignRole('satgas_ppks');

        $ukm = User::factory()->create([
            'name' => 'UKM Olahraga ITG',
            'username' => 'ukmolahraga',
        ]);
        $ukm->assignRole('ormawa');

        $wr3 = User::factory()->create(['name' => 'Wakil Rektor III']);
        $wr3->assignRole('wr3');

        $response = $this->actingAs($satgas)->post(route('satgas.sp.store'), [
            'tipe_sasaran' => 'ormawa',
            'target_user_id' => $ukm->id,
            'nomor_surat' => 'SP/PPKS/2026/002',
            'tingkat' => 'SP-1',
            'perihal' => 'Peringatan Terkait Pelanggaran Kode Etik / Pencegahan Satgas PPKS',
            'alasan_singkat' => 'Pelanggaran SOP dan pelaksanaan kegiatan tanpa mitigasi',
            'deskripsi' => 'Berdasarkan temuan investigasi Satgas PPKS ITG, organisasi bersangkutan telah melanggar prosedur.',
            'sanksi' => 'Teguran tertulis dan pembekuan kegiatan sampai evaluasi selesai.',
            'tanggal_surat' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('satgas.sp.index'));
        $response->assertSessionHas('success');

        $sp = SuratPeringatan::where('nomor_surat', 'SP/PPKS/2026/002')->first();
        $this->assertNotNull($sp);
        $this->assertEquals('ormawa', $sp->tipe_sasaran);
        $this->assertEquals($ukm->id, $sp->target_user_id);
        $this->assertEquals('menunggu_validasi', $sp->status);
        $this->assertEquals('UKM Olahraga ITG', $sp->nama_penerima);
        $this->assertEquals('Satgas PPKS', $sp->penerbit_label);

        // Satgas dapat melihat detail SP
        $responseShow = $this->actingAs($satgas)->get(route('satgas.sp.show', $sp));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('UKM Olahraga ITG');

        // WR3 dapat menyetujui SP untuk Ormawa
        $responseApprove = $this->actingAs($wr3)->post(route('wr3.sp.approve', $sp), [
            'catatan_wr3' => 'Disetujui untuk diterbitkan resmi ke organisasi.',
        ]);
        $responseApprove->assertRedirect(route('wr3.sp.index'));
        $this->assertEquals('disetujui', $sp->fresh()->status);

        // Satgas PPKS dapat mengunduh berkas PDF SP setelah disetujui
        $responsePdf = $this->actingAs($satgas)->get(route('bkhm.sp.pdf', $sp));
        $responsePdf->assertStatus(200);
    }

    public function test_expanded_letter_generator_creates_new_letter_types_for_ukm()
    {
        $ukm = User::factory()->create([
            'name' => 'UKM Robotika ITG',
            'jenis_ormawa' => 'ukm',
        ]);
        $ukm->assignRole('ormawa');

        // 1. Surat Izin Kegiatan
        $respIzin = $this->actingAs($ukm)->post(route('generator.letters.store'), [
            'type' => 'izin_kegiatan',
            'nomor_surat' => '01/UKM/IZIN/2026',
            'perihal' => 'Permohonan Izin Kegiatan Seminar AI',
            'tujuan' => 'Dekan Fakultas Teknik ITG',
            'kalimat_pembuka' => 'Dengan hormat, kami memohon izin kegiatan:',
            'nama_kegiatan' => 'Seminar AI 2026',
            'hari_tanggal' => 'Senin, 1 Juni 2026',
            'waktu' => '08.00 WIB',
            'tempat' => 'Aula Utama',
            'jumlah_peserta' => '100 orang',
            'penanggung_jawab' => 'Ketua Pelaksana',
            'kalimat_penutup' => 'Demikian permohonan kami sampaikan.',
        ]);
        $respIzin->assertRedirect();
        $letterIzin = Letter::where('nomor_surat', '01/UKM/IZIN/2026')->first();
        $this->assertNotNull($letterIzin);
        $this->assertEquals('izin_kegiatan', $letterIzin->type);

        // Preview & PDF view check
        $respShow = $this->actingAs($ukm)->get(route('generator.letters.show', $letterIzin));
        $respShow->assertStatus(200);
        $respShow->assertSee('SURAT PERMOHONAN IZIN KEGIATAN');
        $respShow->assertSee('Seminar AI 2026');

        // 2. Surat Dispensasi
        $respDisp = $this->actingAs($ukm)->post(route('generator.letters.store'), [
            'type' => 'dispensasi',
            'nomor_surat' => '02/UKM/DISP/2026',
            'perihal' => 'Dispensasi Kuliah Delegasi Lomba',
            'tujuan' => 'Dosen Pengampu Mata Kuliah',
            'nama_kegiatan' => 'Gemastik 2026',
            'hari_tanggal' => 'Selasa, 2 Juni 2026',
            'waktu' => 'Sepanjang hari',
            'nama_mahasiswa' => 'Budi Santoso',
            'nim' => '2206001',
            'kelas_prodi' => 'Teknik Informatika',
            'keterangan_tugas' => 'Finalis Lomba Desain Web',
        ]);
        $respDisp->assertRedirect();
        $letterDisp = Letter::where('nomor_surat', '02/UKM/DISP/2026')->first();
        $this->assertNotNull($letterDisp);
        $this->assertEquals('dispensasi', $letterDisp->type);

        $respShowDisp = $this->actingAs($ukm)->get(route('generator.letters.show', $letterDisp));
        $respShowDisp->assertStatus(200);
        $respShowDisp->assertSee('SURAT PERMOHONAN DISPENSASI KULIAH');
        $respShowDisp->assertSee('Budi Santoso');
    }

    public function test_hima_cannot_access_proker_and_redirects_to_dashboard()
    {
        $hima = User::factory()->create([
            'name' => 'HIMATIF ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $hima->assignRole('ormawa');

        $responseIndex = $this->actingAs($hima)->get(route('proker.index'));
        $responseIndex->assertRedirect(route('dashboard'));
        $responseIndex->assertSessionHas('error');

        $responseCreate = $this->actingAs($hima)->get(route('proker.create'));
        $responseCreate->assertRedirect(route('dashboard'));

        $responseStore = $this->actingAs($hima)->post(route('proker.store'), [
            'nama_proker' => 'Informatics Fest 2026',
            'deskripsi' => 'Kompetisi IT tahunan.',
            'rencana_pelaksanaan' => now()->addDays(45)->toDateString(),
        ]);
        $responseStore->assertRedirect(route('dashboard'));
    }

    public function test_hima_cannot_access_letter_generator_except_lpj()
    {
        $hima = User::factory()->create([
            'name' => 'HIMA Informatika ITG',
            'jenis_ormawa' => 'hima',
        ]);
        $hima->assignRole('ormawa');

        // General proposal generator is blocked (redirects to pengajuan.create with notice)
        $this->actingAs($hima)->get(route('generator.create'))
            ->assertRedirect(route('pengajuan.create'));

        // General letters generator is blocked
        $this->actingAs($hima)->get(route('generator.letters.create'))
            ->assertRedirect(route('dashboard'));

        // LPJ generator is allowed
        $this->actingAs($hima)->get(route('generator.lpj.create'))
            ->assertStatus(200);
    }
}
