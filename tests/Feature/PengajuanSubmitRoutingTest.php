<?php

namespace Tests\Feature;

use App\Models\Pengajuan;
use App\Models\User;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PengajuanSubmitRoutingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'WorkflowSeeder', '--force' => true]);
    }

    private function makeDraftPengajuan(User $user): Pengajuan
    {
        $draft = WorkflowState::where('name', WorkflowState::DRAFT)->firstOrFail();

        return Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Uji Routing Submit',
            'dana_diajukan' => 100000,
            'tanggal_pengajuan' => now()->toDateString(),
            'workflow_state_id' => $draft->id,
        ]);
    }

    /** Transisi dari draft untuk pengaju HIMA, BEM & BPM tersedia di seeder. */
    public function test_draft_transitions_for_hima_bem_and_bpm_exist(): void
    {
        $draft = WorkflowState::where('name', WorkflowState::DRAFT)->firstOrFail();
        $prodiReview = WorkflowState::where('name', WorkflowState::PRODI_REVIEW)->firstOrFail();
        $bemApproved = WorkflowState::where('name', WorkflowState::BEM_APPROVED)->firstOrFail();
        $ppksReview = WorkflowState::where('name', WorkflowState::PPKS_REVIEW)->firstOrFail();

        $this->assertTrue(
            WorkflowTransition::where('from_state_id', $draft->id)
                ->where('to_state_id', $prodiReview->id)
                ->where('required_role', 'ormawa')
                ->exists(),
            'Transisi draft -> prodi_review (role ormawa) tidak ditemukan'
        );

        $this->assertTrue(
            WorkflowTransition::where('from_state_id', $draft->id)
                ->where('to_state_id', $bemApproved->id)
                ->where('required_role', 'bem')
                ->exists(),
            'Transisi draft -> bem_approved (role bem) tidak ditemukan'
        );

        $this->assertTrue(
            WorkflowTransition::where('from_state_id', $draft->id)
                ->where('to_state_id', $ppksReview->id)
                ->where('required_role', 'bpm')
                ->exists(),
            'Transisi draft -> ppks_review (role bpm) tidak ditemukan'
        );
    }

    public function test_ormawa_ukm_submit_goes_to_bem(): void
    {
        $ormawa = User::factory()->create([
            'name' => 'UKM Robotika ITG',
            'jenis_ormawa' => 'ukm',
        ]);
        $ormawa->assignRole('ormawa');
        $pengajuan = $this->makeDraftPengajuan($ormawa);

        $response = $this->actingAs($ormawa)->post(route('pengajuan.ajukan', $pengajuan));

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim ke BEM.');
        $this->assertEquals(WorkflowState::SUBMITTED, $pengajuan->fresh()->state->name);
    }

    public function test_hima_submit_goes_to_prodi(): void
    {
        $hima = User::factory()->create([
            'name' => 'HIMA Informatika ITG',
            'prodi' => 'Teknik Informatika',
            'jenis_ormawa' => 'hima',
        ]);
        $hima->assignRole('ormawa');
        $pengajuan = $this->makeDraftPengajuan($hima);

        $response = $this->actingAs($hima)->post(route('pengajuan.ajukan', $pengajuan));

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim ke Pembina Prodi.');
        $this->assertEquals(WorkflowState::PRODI_REVIEW, $pengajuan->fresh()->state->name);
    }

    public function test_prodi_can_verify_and_forward_to_bem(): void
    {
        $hima = User::factory()->create([
            'name' => 'HIMA Informatika ITG',
            'prodi' => 'Teknik Informatika',
            'jenis_ormawa' => 'hima',
        ]);
        $hima->assignRole('ormawa');
        $pengajuan = $this->makeDraftPengajuan($hima);
        $this->actingAs($hima)->post(route('pengajuan.ajukan', $pengajuan));

        $prodi = User::factory()->create([
            'name' => 'Ketua Prodi IF',
            'prodi' => 'Teknik Informatika',
        ]);
        $prodi->assignRole('prodi');

        $prodiReviewState = WorkflowState::where('name', WorkflowState::PRODI_REVIEW)->firstOrFail();
        $submittedState = WorkflowState::where('name', WorkflowState::SUBMITTED)->firstOrFail();
        $transition = WorkflowTransition::where('from_state_id', $prodiReviewState->id)
            ->where('to_state_id', $submittedState->id)
            ->where('required_role', 'prodi')
            ->firstOrFail();

        $response = $this->actingAs($prodi)->post(route('verifikasi.process', $pengajuan), [
            'transition_id' => $transition->id,
            'catatan' => 'Disetujui Pembina Prodi IF.',
        ]);

        $response->assertRedirect();
        $this->assertEquals(WorkflowState::SUBMITTED, $pengajuan->fresh()->state->name);
        $this->assertNotNull($pengajuan->fresh()->verified_prodi_at);
        $this->assertEquals('Disetujui Pembina Prodi IF.', $pengajuan->fresh()->catatan_prodi);
    }

    public function test_bem_submit_goes_to_bpm(): void
    {
        $bem = User::factory()->create();
        $bem->assignRole('bem');
        $pengajuan = $this->makeDraftPengajuan($bem);

        $response = $this->actingAs($bem)->post(route('pengajuan.ajukan', $pengajuan));

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim ke BPM.');
        $this->assertEquals(WorkflowState::BEM_APPROVED, $pengajuan->fresh()->state->name);
    }

    public function test_bpm_submit_goes_to_satgas_ppks(): void
    {
        $bpm = User::factory()->create();
        $bpm->assignRole('bpm');
        $pengajuan = $this->makeDraftPengajuan($bpm);

        $response = $this->actingAs($bpm)->post(route('pengajuan.ajukan', $pengajuan));

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim ke Satgas PPKS.');
        $this->assertEquals(WorkflowState::PPKS_REVIEW, $pengajuan->fresh()->state->name);
    }

    public function test_satgas_ppks_verifies_and_forwards_to_bkhm(): void
    {
        $ormawa = User::factory()->create();
        $ormawa->assignRole('ormawa');
        $pengajuan = $this->makeDraftPengajuan($ormawa);

        $ppksState = WorkflowState::where('name', WorkflowState::PPKS_REVIEW)->firstOrFail();
        $pengajuan->update(['workflow_state_id' => $ppksState->id]);

        $satgas = User::factory()->create(['name' => 'Ketua Satgas PPKS ITG']);
        $satgas->assignRole('satgas_ppks');

        $bpmApprovedState = WorkflowState::where('name', WorkflowState::BPM_APPROVED)->firstOrFail();
        $transition = WorkflowTransition::where('from_state_id', $ppksState->id)
            ->where('to_state_id', $bpmApprovedState->id)
            ->where('required_role', 'satgas_ppks')
            ->firstOrFail();

        $response = $this->actingAs($satgas)->post(route('verifikasi.process', $pengajuan), [
            'transition_id' => $transition->id,
            'catatan' => 'Dokumen mitigasi PPKS lengkap dan disetujui.',
        ]);

        $response->assertRedirect();
        $this->assertEquals(WorkflowState::BPM_APPROVED, $pengajuan->fresh()->state->name);
        $this->assertNotNull($pengajuan->fresh()->verified_ppks_at);
        $this->assertEquals('Dokumen mitigasi PPKS lengkap dan disetujui.', $pengajuan->fresh()->catatan_ppks);
        $this->assertNotNull($pengajuan->fresh()->tandaTanganPpks());
    }
}
