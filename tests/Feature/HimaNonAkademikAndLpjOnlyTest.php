<?php

namespace Tests\Feature;

use App\Models\MasterRuangan;
use App\Models\MasterBarang;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\WorkflowState;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WorkflowSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HimaNonAkademikAndLpjOnlyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(WorkflowSeeder::class);
    }

    private function createHimaUser(): User
    {
        $user = User::factory()->create([
            'name' => 'HIMA Sistem Informasi ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Sistem Informasi',
            'kode_prodi' => 'SI',
            'saldo' => 5000000,
        ]);
        $user->assignRole('ormawa');

        return $user;
    }

    private function createUkmUser(): User
    {
        $user = User::factory()->create([
            'name' => 'UKM Olahraga ITG',
            'jenis_ormawa' => 'ukm',
            'saldo' => 5000000,
        ]);
        $user->assignRole('ormawa');

        return $user;
    }

    public function test_prodi_role_does_not_exist(): void
    {
        $this->assertDatabaseMissing('roles', ['name' => 'prodi']);
        $this->assertDatabaseMissing('workflow_states', ['name' => 'prodi_review']);
    }

    public function test_hima_can_access_pengajuan_dana_non_akademik_and_submits_directly_to_bem(): void
    {
        $hima = $this->createHimaUser();

        // 1. Can view index and create form
        $this->actingAs($hima)->get(route('pengajuan.index'))->assertStatus(200);
        $this->actingAs($hima)->get(route('pengajuan.create'))->assertStatus(200);

        // 2. Draft submission goes directly to BEM (submitted)
        $draftState = WorkflowState::where('name', WorkflowState::DRAFT)->firstOrFail();
        $pengajuan = Pengajuan::create([
            'user_id' => $hima->id,
            'nama_kegiatan' => 'Kegiatan Non-Akademik HIMA Fest',
            'dana_diajukan' => 1500000,
            'tanggal_pengajuan' => now()->toDateString(),
            'workflow_state_id' => $draftState->id,
        ]);

        $response = $this->actingAs($hima)->post(route('pengajuan.ajukan', $pengajuan));
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim ke BEM.');

        $pengajuan->refresh();
        $this->assertEquals(WorkflowState::SUBMITTED, $pengajuan->state->name);
    }

    public function test_hima_can_access_lpj_generator_and_lpj_monitoring(): void
    {
        $hima = $this->createHimaUser();

        $this->actingAs($hima)->get(route('generator.lpj.create'))->assertStatus(200);
        $this->actingAs($hima)->get(route('lpj.index'))->assertStatus(200);
    }

    public function test_hima_is_blocked_from_sarpras_features(): void
    {
        $hima = $this->createHimaUser();

        // Peminjaman Ruangan
        $this->actingAs($hima)->get(route('peminjaman.tempat.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->get(route('peminjaman.tempat.create'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->post(route('peminjaman.tempat.store'), [])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        // Peminjaman Barang
        $this->actingAs($hima)->get(route('peminjaman.barang.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->get(route('peminjaman.barang.create'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->post(route('peminjaman.barang.store'), [])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    public function test_hima_is_blocked_from_program_kerja(): void
    {
        $hima = $this->createHimaUser();

        $this->actingAs($hima)->get(route('proker.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->get(route('proker.create'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->post(route('proker.store'), [
            'nama_proker' => 'Proker HIMA',
            'deskripsi' => 'Deskripsi',
            'rencana_pelaksanaan' => now()->addDays(10)->toDateString(),
        ])->assertRedirect(route('dashboard'))->assertSessionHas('error');
    }

    public function test_hima_is_blocked_from_pelaporan_prestasi(): void
    {
        $hima = $this->createHimaUser();

        $this->actingAs($hima)->get(route('prestasi.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->get(route('prestasi.create'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($hima)->post(route('prestasi.store'), [])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    public function test_hima_is_blocked_from_general_letters(): void
    {
        $hima = $this->createHimaUser();

        $this->actingAs($hima)->get(route('generator.create'))
            ->assertRedirect(route('pengajuan.create'));

        $this->actingAs($hima)->get(route('generator.letters.create'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    public function test_ukm_still_has_access_to_sarpras_proker_and_prestasi(): void
    {
        $ukm = $this->createUkmUser();

        $this->actingAs($ukm)->get(route('peminjaman.tempat.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('peminjaman.barang.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('proker.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('prestasi.index'))->assertStatus(200);
    }
}

