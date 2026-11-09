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

    public function test_admin_can_create_prodi_account_with_kode_prodi()
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

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'prodi_ts',
            'prodi' => 'Teknik Sipil',
            'kode_prodi' => 'TS',
        ]);

        $user = User::where('username', 'prodi_ts')->first();
        $this->assertTrue($user->hasRole('prodi'));
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

    public function test_prodi_receives_in_app_notification_when_matching_hima_submits_proker()
    {
        $prodiIf = User::factory()->create([
            'name' => 'Prodi Teknik Informatika',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $prodiIf->assignRole('prodi');

        $prodiSi = User::factory()->create([
            'name' => 'Prodi Sistem Informasi',
            'prodi' => 'Sistem Informasi',
            'kode_prodi' => 'SI',
        ]);
        $prodiSi->assignRole('prodi');

        $himaIf = User::factory()->create([
            'name' => 'HIMATIF ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $himaIf->assignRole('ormawa');

        $response = $this->actingAs($himaIf)->post(route('proker.store'), [
            'nama_proker' => 'Informatics Bootcamp 2026',
            'deskripsi' => 'Pelatihan intensif coding untuk mahasiswa baru IF.',
            'rencana_pelaksanaan' => now()->addDays(30)->toDateString(),
        ]);

        $response->assertRedirect(route('proker.index'));
        $this->assertDatabaseHas('program_kerjas', [
            'user_id' => $himaIf->id,
            'nama_proker' => 'Informatics Bootcamp 2026',
        ]);

        // Prodi IF menerima notifikasi
        $notifIf = Notifikasi::where('user_id', $prodiIf->id)->first();
        $this->assertNotNull($notifIf);
        $this->assertStringContainsString('Pemberitahuan Proker Baru', $notifIf->pesan);
        $this->assertStringContainsString('Informatics Bootcamp 2026', $notifIf->pesan);

        // Prodi SI tidak menerima notifikasi
        $notifSi = Notifikasi::where('user_id', $prodiSi->id)->count();
        $this->assertEquals(0, $notifSi);
    }

    public function test_prodi_can_only_view_prokers_from_their_own_study_program()
    {
        $prodiIf = User::factory()->create([
            'name' => 'Prodi IF',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $prodiIf->assignRole('prodi');

        $himaIf = User::factory()->create(['prodi' => 'Teknik Informatika', 'kode_prodi' => 'IF']);
        $himaIf->assignRole('ormawa');
        $prokerIf = ProgramKerja::create([
            'user_id' => $himaIf->id,
            'nama_proker' => 'Lomba Web Development IF',
            'deskripsi' => 'Deskripsi IF',
            'rencana_pelaksanaan' => now()->addDays(10)->toDateString(),
            'status' => 'rencana',
        ]);

        $himaSi = User::factory()->create(['prodi' => 'Sistem Informasi', 'kode_prodi' => 'SI']);
        $himaSi->assignRole('ormawa');
        $prokerSi = ProgramKerja::create([
            'user_id' => $himaSi->id,
            'nama_proker' => 'Seminar Enterprise SI',
            'deskripsi' => 'Deskripsi SI',
            'rencana_pelaksanaan' => now()->addDays(12)->toDateString(),
            'status' => 'rencana',
        ]);

        $response = $this->actingAs($prodiIf)->get(route('proker.index'));
        $response->assertStatus(200);
        $response->assertSee('Lomba Web Development IF');
        $response->assertDontSee('Seminar Enterprise SI');
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

    public function test_expanded_letter_generator_creates_new_letter_types()
    {
        $ormawa = User::factory()->create(['name' => 'HIMATIF ITG']);
        $ormawa->assignRole('ormawa');

        // 1. Surat Izin Kegiatan
        $respIzin = $this->actingAs($ormawa)->post(route('generator.letters.store'), [
            'type' => 'izin_kegiatan',
            'nomor_surat' => '01/HIMA/IZIN/2026',
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
        $letterIzin = Letter::where('nomor_surat', '01/HIMA/IZIN/2026')->first();
        $this->assertNotNull($letterIzin);
        $this->assertEquals('izin_kegiatan', $letterIzin->type);

        // Preview & PDF view check
        $respShow = $this->actingAs($ormawa)->get(route('generator.letters.show', $letterIzin));
        $respShow->assertStatus(200);
        $respShow->assertSee('SURAT PERMOHONAN IZIN KEGIATAN');
        $respShow->assertSee('Seminar AI 2026');

        // 2. Surat Dispensasi
        $respDisp = $this->actingAs($ormawa)->post(route('generator.letters.store'), [
            'type' => 'dispensasi',
            'nomor_surat' => '02/HIMA/DISP/2026',
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
        $letterDisp = Letter::where('nomor_surat', '02/HIMA/DISP/2026')->first();
        $this->assertNotNull($letterDisp);
        $this->assertEquals('dispensasi', $letterDisp->type);

        $respShowDisp = $this->actingAs($ormawa)->get(route('generator.letters.show', $letterDisp));
        $respShowDisp->assertStatus(200);
        $respShowDisp->assertSee('SURAT PERMOHONAN DISPENSASI KULIAH');
        $respShowDisp->assertSee('Budi Santoso');
    }

    public function test_hima_submits_proker_sets_status_prodi_to_menunggu()
    {
        $hima = User::factory()->create([
            'name' => 'HIMATIF ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $hima->assignRole('ormawa');

        $this->actingAs($hima)->post(route('proker.store'), [
            'nama_proker' => 'Informatics Fest 2026',
            'deskripsi' => 'Kompetisi IT tahunan.',
            'rencana_pelaksanaan' => now()->addDays(45)->toDateString(),
        ])->assertRedirect(route('proker.index'));

        $proker = ProgramKerja::where('nama_proker', 'Informatics Fest 2026')->first();
        $this->assertNotNull($proker);
        $this->assertEquals('menunggu', $proker->status_prodi);
    }

    public function test_prodi_can_acc_hima_proker_and_hima_receives_notification()
    {
        $prodi = User::factory()->create([
            'name' => 'Kaprodi Teknik Informatika',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $prodi->assignRole('prodi');

        $hima = User::factory()->create([
            'name' => 'HIMATIF ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $hima->assignRole('ormawa');

        $proker = ProgramKerja::create([
            'user_id' => $hima->id,
            'nama_proker' => 'Workshop UI/UX Design',
            'deskripsi' => 'Workshop Figma bersama praktisi industri.',
            'rencana_pelaksanaan' => now()->addDays(20)->toDateString(),
            'status' => 'rencana',
            'status_prodi' => 'menunggu',
        ]);

        $response = $this->actingAs($prodi)->put(route('proker.acc-prodi', $proker), [
            'status_prodi' => 'disetujui',
            'catatan_prodi' => 'Disetujui. Pastikan tidak bentrok dengan jadwal UTS.',
        ]);

        $response->assertRedirect(route('proker.index'));

        $proker->refresh();
        $this->assertEquals('disetujui', $proker->status_prodi);
        $this->assertEquals($prodi->id, $proker->prodi_id);
        $this->assertNotNull($proker->disetujui_prodi_at);
        $this->assertEquals('Disetujui. Pastikan tidak bentrok dengan jadwal UTS.', $proker->catatan_prodi);

        // HIMA menerima notifikasi bahwa proker di-ACC
        $notif = Notifikasi::where('user_id', $hima->id)->latest()->first();
        $this->assertNotNull($notif);
        $this->assertStringContainsString('Program Kerja Di-ACC', $notif->pesan);
        $this->assertStringContainsString('Workshop UI/UX Design', $notif->pesan);
    }

    public function test_prodi_cannot_acc_proker_of_another_study_program()
    {
        $prodiSi = User::factory()->create([
            'name' => 'Kaprodi Sistem Informasi',
            'prodi' => 'Sistem Informasi',
            'kode_prodi' => 'SI',
        ]);
        $prodiSi->assignRole('prodi');

        $himaIf = User::factory()->create([
            'name' => 'HIMATIF ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $himaIf->assignRole('ormawa');

        $prokerIf = ProgramKerja::create([
            'user_id' => $himaIf->id,
            'nama_proker' => 'AI Bootcamp IF',
            'deskripsi' => 'Bootcamp AI.',
            'rencana_pelaksanaan' => now()->addDays(15)->toDateString(),
            'status' => 'rencana',
            'status_prodi' => 'menunggu',
        ]);

        // Kaprodi SI tidak berhak ACC proker milik HIMA IF
        $this->actingAs($prodiSi)->put(route('proker.acc-prodi', $prokerIf), [
            'status_prodi' => 'disetujui',
        ])->assertForbidden();

        $prokerIf->refresh();
        $this->assertEquals('menunggu', $prokerIf->status_prodi);
    }

    public function test_hima_cannot_submit_proposal_for_unapproved_proker_until_acc()
    {
        $prodi = User::factory()->create([
            'name' => 'Kaprodi Teknik Informatika',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
        ]);
        $prodi->assignRole('prodi');

        $hima = User::factory()->create([
            'name' => 'HIMATIF ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'kode_prodi' => 'IF',
            'saldo' => 10000000,
        ]);
        $hima->assignRole('ormawa');

        $proker = ProgramKerja::create([
            'user_id' => $hima->id,
            'nama_proker' => 'Seminar Cloud Computing',
            'deskripsi' => 'Seminar bersama AWS Community.',
            'rencana_pelaksanaan' => now()->addDays(25)->toDateString(),
            'status' => 'rencana',
            'status_prodi' => 'menunggu', // Belum di-ACC
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('proposal.pdf', 300, 'application/pdf');

        // 1. Coba ajukan proposal dengan proker yang statusnya masih 'menunggu'
        $respPending = $this->actingAs($hima)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Seminar Cloud Computing 2026',
            'dana_diajukan' => 1500000,
            'tanggal_pengajuan' => now()->toDateString(),
            'program_kerja_id' => $proker->id,
            'file_proposal' => $file,
        ]);

        $respPending->assertSessionHasErrors('program_kerja_id');

        // 2. Sekarang Kaprodi menyetujui (ACC) proker tersebut
        $this->actingAs($prodi)->put(route('proker.acc-prodi', $proker), [
            'status_prodi' => 'disetujui',
        ])->assertRedirect();

        // 3. Setelah di-ACC, HIMA berhasil mengajukan proposal
        $respSuccess = $this->actingAs($hima)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Seminar Cloud Computing 2026',
            'dana_diajukan' => 1500000,
            'tanggal_pengajuan' => now()->toDateString(),
            'program_kerja_id' => $proker->id,
            'file_proposal' => $file,
        ]);

        $respSuccess->assertRedirect(route('pengajuan.index'));
        $this->assertDatabaseHas('pengajuan', [
            'nama_kegiatan' => 'Seminar Cloud Computing 2026',
            'program_kerja_id' => $proker->id,
        ]);
    }
}

