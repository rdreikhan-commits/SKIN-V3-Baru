<?php

namespace Tests\Feature;

use App\Models\HistoriStatus;
use App\Models\Pengajuan;
use App\Models\ProposalOtomatis;
use App\Models\ProposalRab;
use App\Models\User;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermohonanDanaNonKegiatanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'WorkflowSeeder', '--force' => true]);
        Storage::fake('local');
    }

    private function createUser(string $role, array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'saldo' => 10000000,
            'saldo_awal' => 10000000,
            'status_akun' => 'aktif',
        ], $attributes));
        $user->assignRole($role);
        return $user;
    }

    public function test_ormawa_can_create_pengadaan_barang_without_satgas_ppks_documents(): void
    {
        $ormawa = $this->createUser('ormawa', ['name' => 'UKM Robotika ITG']);
        $draftState = WorkflowState::where('name', 'draft')->first();

        $response = $this->actingAs($ormawa)->post(route('pengajuan.store'), [
            'jenis_pengajuan' => 'pengadaan_barang',
            'nama_kegiatan' => 'Pengadaan Komponen Sensor & Mikrokontroler Robotika',
            'dana_diajukan' => 3500000,
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'file_proposal' => UploadedFile::fake()->create('proposal_pengadaan.pdf', 1000, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('pengajuan.index'));

        $pengajuan = Pengajuan::where('nama_kegiatan', 'Pengadaan Komponen Sensor & Mikrokontroler Robotika')->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('pengadaan_barang', $pengajuan->jenis_pengajuan);
        $this->assertTrue($pengajuan->isPengadaanBarang());
        $this->assertTrue($pengajuan->isBypassPpks());
        $this->assertFalse($pengajuan->isKegiatan());
        $this->assertNull($pengajuan->file_pakta_ppks);
    }

    public function test_ormawa_can_create_pendanaan_lomba_without_satgas_ppks_documents(): void
    {
        $ormawa = $this->createUser('ormawa', ['name' => 'BEM REMA ITG']);

        $response = $this->actingAs($ormawa)->post(route('pengajuan.store'), [
            'jenis_pengajuan' => 'pendanaan_lomba',
            'nama_kegiatan' => 'Pendanaan Delegasi Finalis GEMASTIK 2026',
            'dana_diajukan' => 4500000,
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'tanggal_mulai_kegiatan' => now()->addDays(14)->format('Y-m-d'),
            'tanggal_selesai_kegiatan' => now()->addDays(16)->format('Y-m-d'),
            'file_proposal' => UploadedFile::fake()->create('proposal_delegasi_lomba.pdf', 1000, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('pengajuan.index'));

        $pengajuan = Pengajuan::where('nama_kegiatan', 'Pendanaan Delegasi Finalis GEMASTIK 2026')->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('pendanaan_lomba', $pengajuan->jenis_pengajuan);
        $this->assertTrue($pengajuan->isPendanaanLomba());
        $this->assertTrue($pengajuan->isBypassPpks());
        $this->assertNull($pengajuan->file_pakta_ppks);
    }

    public function test_bpm_verifying_pengadaan_barang_sees_direct_bkhm_transition_and_bypasses_satgas_ppks(): void
    {
        $ormawa = $this->createUser('ormawa', ['name' => 'UKM Musik ITG']);
        $bpm = $this->createUser('bpm', ['name' => 'Komisi Anggaran BPM']);
        $satgas = $this->createUser('satgas_ppks', ['name' => 'Satgas PPKS ITG']);
        $bkhm = $this->createUser('bkhm', ['name' => 'Biro Kemahasiswaan']);

        $bemApprovedState = WorkflowState::where('name', 'bem_approved')->first();
        $bpmApprovedState = WorkflowState::where('name', 'bpm_approved')->first();
        $ppksReviewState = WorkflowState::where('name', 'ppks_review')->first();

        // Buat pengajuan pengadaan barang pada state bem_approved
        $pengajuan = Pengajuan::create([
            'user_id' => $ormawa->id,
            'jenis_pengajuan' => 'pengadaan_barang',
            'nama_kegiatan' => 'Pengadaan Sound System Portable & Mic Studio',
            'dana_diajukan' => 2800000,
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'workflow_state_id' => $bemApprovedState->id,
            'unique_code' => 'PENGADAAN01',
            'file_proposal' => 'proposals/dummy.pdf',
        ]);

        // BPM membuka halaman verifikasi show
        $viewResponse = $this->actingAs($bpm)->get(route('verifikasi.show', $pengajuan));
        $viewResponse->assertOk();

        $availableTransitions = $viewResponse->viewData('availableTransitions');
        
        // Transisi ke BKHM (bpm_approved) harus TERSEDIA
        $toBkhm = $availableTransitions->first(fn($t) => $t->toState->name === 'bpm_approved');
        $this->assertNotNull($toBkhm, 'Transisi langsung ke BKHM (bpm_approved) harus tersedia untuk BPM pada pengadaan barang.');

        // Transisi ke Satgas PPKS (ppks_review) TIDAK BOLEH TERSEDIA
        $toPpks = $availableTransitions->first(fn($t) => $t->toState->name === 'ppks_review');
        $this->assertNull($toPpks, 'Transisi ke Satgas PPKS tidak boleh muncul untuk permohonan dana non-kegiatan.');

        // BPM memproses verifikasi dan menyetujui langsung ke BKHM
        $processResponse = $this->actingAs($bpm)->post(route('verifikasi.process', $pengajuan), [
            'transition_id' => $toBkhm->id,
            'catatan' => 'Disetujui BPM: Spesifikasi dan anggaran barang telah sesuai kebutuhan ormawa.',
        ]);

        $processResponse->assertRedirect(route('verifikasi.index'));

        // Cek bahwa state pengajuan kini sudah di bpm_approved (antrean BKHM)
        $pengajuan->refresh();
        $this->assertEquals($bpmApprovedState->id, $pengajuan->workflow_state_id);

        // Satgas PPKS tidak melihat proposal ini dalam antreannya
        $satgasIndexResponse = $this->actingAs($satgas)->get(route('verifikasi.index'));
        $satgasPengajuans = $satgasIndexResponse->viewData('pengajuans');
        $this->assertFalse(
            $satgasPengajuans->contains('id', $pengajuan->id),
            'Satgas PPKS tidak boleh menerima proposal permohonan dana pengadaan barang dalam antreannya.'
        );

        // BKHM melihat proposal ini dalam antreannya
        $bkhmIndexResponse = $this->actingAs($bkhm)->get(route('verifikasi.index'));
        $bkhmPengajuans = $bkhmIndexResponse->viewData('pengajuans');
        $this->assertTrue(
            $bkhmPengajuans->contains('id', $pengajuan->id),
            'BKHM harus menerima proposal pengadaan barang langsung dari BPM.'
        );
    }

    public function test_bpm_cannot_transition_non_kegiatan_to_ppks_review(): void
    {
        $ormawa = $this->createUser('ormawa');
        $bpm = $this->createUser('bpm');

        $bemApprovedState = WorkflowState::where('name', 'bem_approved')->first();
        $ppksReviewState = WorkflowState::where('name', 'ppks_review')->first();

        $pengajuan = Pengajuan::create([
            'user_id' => $ormawa->id,
            'jenis_pengajuan' => 'pendanaan_lomba',
            'nama_kegiatan' => 'Pendanaan Kontingen Lomba Debat Nasional',
            'dana_diajukan' => 2000000,
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'workflow_state_id' => $bemApprovedState->id,
            'unique_code' => 'LOMBA001',
            'file_proposal' => 'proposals/dummy.pdf',
        ]);

        // Ambil transisi bem_approved -> ppks_review
        $transitionToPpks = WorkflowTransition::where('from_state_id', $bemApprovedState->id)
            ->where('to_state_id', $ppksReviewState->id)
            ->where('required_role', 'bpm')
            ->first();

        // Mencoba memaksa transisi ke ppks_review harus ditolak 403
        $response = $this->actingAs($bpm)->post(route('verifikasi.process', $pengajuan), [
            'transition_id' => $transitionToPpks->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_kegiatan_mahasiswa_still_routes_through_satgas_ppks(): void
    {
        $ormawa = $this->createUser('ormawa');
        $bpm = $this->createUser('bpm');

        $bemApprovedState = WorkflowState::where('name', 'bem_approved')->first();
        $bpmApprovedState = WorkflowState::where('name', 'bpm_approved')->first();
        $ppksReviewState = WorkflowState::where('name', 'ppks_review')->first();

        $pengajuan = Pengajuan::create([
            'user_id' => $ormawa->id,
            'jenis_pengajuan' => 'kegiatan',
            'nama_kegiatan' => 'Malam Keakraban & LKMM Mahasiswa Baru',
            'dana_diajukan' => 5000000,
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'workflow_state_id' => $bemApprovedState->id,
            'unique_code' => 'KEGIATAN01',
            'file_proposal' => 'proposals/dummy.pdf',
        ]);

        // BPM membuka verifikasi show untuk proposal kegiatan
        $viewResponse = $this->actingAs($bpm)->get(route('verifikasi.show', $pengajuan));
        $availableTransitions = $viewResponse->viewData('availableTransitions');

        // Untuk kegiatan, transisi langsung ke BKHM (bpm_approved) TIDAK BOLEH muncul
        $toBkhm = $availableTransitions->first(fn($t) => $t->toState->name === 'bpm_approved');
        $this->assertNull($toBkhm, 'Kegiatan mahasiswa tidak boleh langsung ke BKHM tanpa Satgas PPKS.');

        // Transisi ke Satgas PPKS harus muncul
        $toPpks = $availableTransitions->first(fn($t) => $t->toState->name === 'ppks_review');
        $this->assertNotNull($toPpks, 'Kegiatan mahasiswa wajib memiliki transisi ke Satgas PPKS.');
    }

    public function test_proposal_generator_supports_pengadaan_barang_and_pendanaan_lomba(): void
    {
        $user = $this->createUser('ormawa', [
            'nama_ketua' => 'Ketua Ormawa',
            'nama_sekretaris' => 'Sekretaris Ormawa',
            'nama_bendahara' => 'Bendahara Ormawa',
        ]);

        // 1. Simpan proposal pengadaan barang
        $responseBarang = $this->actingAs($user)->post(route('generator.store'), [
            'jenis_proposal' => 'pengadaan_barang',
            'nama_kegiatan' => 'Pengadaan Fasilitas Sekretariat Ormawa 2026',
            'latar_belakang' => 'Latar belakang pengadaan alat...',
            'tujuan' => 'Tujuan pengadaan barang...',
            'sasaran' => 'Seluruh anggota ormawa...',
            'indikator' => 'Ketersediaan barang 100% baik',
            'luaran' => 'Kartu inventaris dan barang fisik',
            'dampak' => 'Meningkatkan kenyamanan kerja ormawa',
            'penutup' => 'Demikian proposal pengadaan ini dibuat...',
            'action' => 'draft',
            'rab_rincian' => ['Proyektor Full HD', 'Lemari Arsip'],
            'rab_vol' => [1, 1],
            'rab_sat' => ['Unit', 'Unit'],
            'rab_harga' => [4500000, 1500000],
            'pan_jabatan' => ['Koordinator Pengadaan'],
            'pan_nama' => ['Wildan Saputra'],
        ]);

        $responseBarang->assertSessionHasNoErrors();
        $proposalBarang = ProposalOtomatis::where('nama_kegiatan', 'Pengadaan Fasilitas Sekretariat Ormawa 2026')->first();
        $this->assertNotNull($proposalBarang);
        $this->assertEquals('pengadaan_barang', $proposalBarang->jenis_proposal);
        $this->assertTrue($proposalBarang->isPengadaanBarang());
        $this->assertEquals('PROPOSAL PENGAJUAN DANA PENGADAAN BARANG & INVENTARIS', $proposalBarang->judul_dokumen);

        // Cetak view harus memuat judul khusus pengadaan barang
        $printResponse = $this->actingAs($user)->get(route('generator.print', $proposalBarang));
        $printResponse->assertOk();
        $printResponse->assertSee('PROPOSAL PENGAJUAN DANA PENGADAAN BARANG &amp; INVENTARIS', false);

        // 2. Simpan proposal pendanaan lomba
        $responseLomba = $this->actingAs($user)->post(route('generator.store'), [
            'jenis_proposal' => 'pendanaan_lomba',
            'nama_kegiatan' => 'Delegasi Finalis Kompetisi Coding Nasional 2026',
            'latar_belakang' => 'Latar belakang delegasi...',
            'tujuan' => 'Tujuan delegasi lomba...',
            'sasaran' => 'Tim kontingen mahasiswa ITG...',
            'indikator' => 'Meraih juara nasional',
            'luaran' => 'Sertifikat dan piala',
            'dampak' => 'Mengharumkan nama almamater',
            'penutup' => 'Demikian proposal delegasi ini dibuat...',
            'action' => 'draft',
            'rab_rincian' => ['Biaya Pendaftaran', 'Tiket Transportasi'],
            'rab_vol' => [1, 3],
            'rab_sat' => ['Tim', 'Orang'],
            'rab_harga' => [500000, 300000],
            'pan_jabatan' => ['Ketua Delegasi'],
            'pan_nama' => ['Rizki Maulana'],
        ]);

        $responseLomba->assertSessionHasNoErrors();
        $proposalLomba = ProposalOtomatis::where('nama_kegiatan', 'Delegasi Finalis Kompetisi Coding Nasional 2026')->first();
        $this->assertNotNull($proposalLomba);
        $this->assertEquals('pendanaan_lomba', $proposalLomba->jenis_proposal);
        $this->assertTrue($proposalLomba->isPendanaanLomba());
        $this->assertEquals('PROPOSAL PENGAJUAN DANA DELEGASI LOMBA & KOMPETISI', $proposalLomba->judul_dokumen);

        // Cetak view harus memuat judul khusus delegasi lomba
        $printLomba = $this->actingAs($user)->get(route('generator.print', $proposalLomba));
        $printLomba->assertOk();
        $printLomba->assertSee('PROPOSAL PENGAJUAN DANA DELEGASI LOMBA &amp; KOMPETISI', false);
    }

    public function test_pengajuan_create_can_prefill_from_generated_proposal(): void
    {
        $user = $this->createUser('ormawa');

        $proposal = ProposalOtomatis::create([
            'user_id' => $user->id,
            'jenis_proposal' => 'pengadaan_barang',
            'nama_kegiatan' => 'Pengadaan Lemari Arsip Ormawa',
            'latar_belakang' => 'Latar belakang...',
            'tujuan' => 'Tujuan...',
            'sasaran' => 'Sasaran...',
            'penutup' => 'Penutup...',
            'status' => 'draft',
        ]);

        ProposalRab::create([
            'proposal_id' => $proposal->id,
            'rincian' => 'Lemari Arsip Besi 4 Laci',
            'volume' => 2,
            'satuan' => 'Unit',
            'harga_satuan' => 1500000,
            'total_harga' => 3000000,
        ]);

        $response = $this->actingAs($user)->get(route('pengajuan.create', ['proposal_id' => $proposal->id]));
        $response->assertOk();
        $response->assertSee('Pengadaan Lemari Arsip Ormawa');
        $response->assertSee('3000000');
        $response->assertSee('Data Terhubung dari Generator Proposal');
    }
}
