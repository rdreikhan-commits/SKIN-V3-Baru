<?php

namespace Tests\Feature;

use App\Models\HimaFiturSetting;
use App\Models\User;
use Database\Seeders\HimaFiturSettingSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WorkflowSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HimaFiturManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(WorkflowSeeder::class);
        $this->seed(HimaFiturSettingSeeder::class);
    }

    private function createBkhmUser(): User
    {
        $user = User::factory()->create([
            'name' => 'Staf BKHM ITG',
            'username' => 'bkhm_staff',
            'email' => 'bkhm@itg.ac.id',
        ]);
        $user->assignRole('bkhm');
        return $user;
    }

    private function createHimaUser(string $name = 'HIMA Informatika ITG', string $kodeProdi = 'IF'): User
    {
        $user = User::factory()->create([
            'name' => $name,
            'jenis_ormawa' => 'hima',
            'prodi' => 'Informatika',
            'kode_prodi' => $kodeProdi,
            'saldo' => 5000000,
        ]);
        $user->assignRole('ormawa');
        return $user;
    }

    private function createUkmUser(): User
    {
        $user = User::factory()->create([
            'name' => 'UKM Robotik ITG',
            'jenis_ormawa' => 'ukm',
            'saldo' => 5000000,
        ]);
        $user->assignRole('ormawa');
        return $user;
    }

    public function test_bkhm_can_view_hima_fitur_settings_page(): void
    {
        $bkhm = $this->createBkhmUser();
        $hima = $this->createHimaUser();

        $response = $this->actingAs($bkhm)->get(route('bkhm.hima-fitur.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Fitur Himpunan Mahasiswa (HIMA)');
        $response->assertSee('Pengajuan Proposal & Dana');
        $response->assertSee('Sarpras (Peminjaman Fasilitas & Barang)');
        $response->assertSee('Program Kerja (Proker)');
        $response->assertSee('Persuratan Digital & Generator Dokumen');
        $response->assertSee($hima->name);
    }

    public function test_non_bkhm_cannot_access_hima_fitur_settings_page(): void
    {
        $hima = $this->createHimaUser();
        $response = $this->actingAs($hima)->get(route('bkhm.hima-fitur.index'));
        $response->assertStatus(403);

        $ukm = $this->createUkmUser();
        $response = $this->actingAs($ukm)->get(route('bkhm.hima-fitur.index'));
        $response->assertStatus(403);
    }

    public function test_bkhm_can_toggle_features_for_hima(): void
    {
        $bkhm = $this->createBkhmUser();

        // Matikan semua fitur kecuali pengajuan dan lpj secara global
        $response = $this->actingAs($bkhm)->post(route('bkhm.hima-fitur.update'), [
            'target' => 'all',
            'aktif_fitur' => ['pengajuan', 'lpj'],
        ]);

        $response->assertRedirect(route('bkhm.hima-fitur.index', ['target' => 'all']));
        $response->assertSessionHas('success');

        $this->assertTrue(HimaFiturSetting::isAktif('pengajuan'));
        $this->assertTrue(HimaFiturSetting::isAktif('lpj'));
        $this->assertFalse(HimaFiturSetting::isAktif('sarpras'));
        $this->assertFalse(HimaFiturSetting::isAktif('proker'));
        $this->assertFalse(HimaFiturSetting::isAktif('persuratan'));
        $this->assertFalse(HimaFiturSetting::isAktif('prestasi'));
    }

    public function test_hima_has_access_when_features_are_enabled_by_bkhm(): void
    {
        // Secara default seeder mengaktifkan semua fitur
        $hima = $this->createHimaUser();

        // 1. Sarpras
        $this->actingAs($hima)->get(route('peminjaman.tempat.index'))->assertStatus(200);
        $this->actingAs($hima)->get(route('peminjaman.barang.index'))->assertStatus(200);

        // 2. Proker
        $this->actingAs($hima)->get(route('proker.index'))->assertStatus(200);

        // 3. Prestasi
        $this->actingAs($hima)->get(route('prestasi.index'))->assertStatus(200);

        // 4. Persuratan Digital
        $this->actingAs($hima)->get(route('generator.create'))->assertStatus(200);
        $this->actingAs($hima)->get(route('generator.letters.create'))->assertStatus(200);

        // 5. LPJ & Pengajuan
        $this->actingAs($hima)->get(route('pengajuan.index'))->assertStatus(200);
        $this->actingAs($hima)->get(route('lpj.index'))->assertStatus(200);
    }

    public function test_hima_is_blocked_when_feature_is_disabled_by_bkhm(): void
    {
        $bkhm = $this->createBkhmUser();
        $hima = $this->createHimaUser();

        // Nonaktifkan sarpras dan proker secara global
        $this->actingAs($bkhm)->post(route('bkhm.hima-fitur.update'), [
            'target' => 'all',
            'aktif_fitur' => ['pengajuan', 'lpj', 'persuratan', 'prestasi'],
        ]);

        // Coba akses sarpras -> dialihkan
        $response = $this->actingAs($hima)->get(route('peminjaman.tempat.index'));
        $response->assertRedirect(route('dashboard'));

        // Coba akses proker -> dialihkan
        $response = $this->actingAs($hima)->get(route('proker.index'));
        $response->assertRedirect(route('dashboard'));

        // Fitur yang masih aktif tetap dapat diakses
        $this->actingAs($hima)->get(route('prestasi.index'))->assertStatus(200);
        $this->actingAs($hima)->get(route('pengajuan.index'))->assertStatus(200);
    }

    public function test_ukm_is_not_affected_by_hima_fitur_toggles(): void
    {
        $bkhm = $this->createBkhmUser();
        $ukm = $this->createUkmUser();

        // Nonaktifkan semua fitur untuk HIMA
        $this->actingAs($bkhm)->post(route('bkhm.hima-fitur.update'), [
            'target' => 'all',
            'aktif_fitur' => [],
        ]);

        // UKM tetap dapat mengakses seluruh fitur
        $this->actingAs($ukm)->get(route('peminjaman.tempat.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('peminjaman.barang.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('proker.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('prestasi.index'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('generator.create'))->assertStatus(200);
        $this->actingAs($ukm)->get(route('generator.letters.create'))->assertStatus(200);
    }

    public function test_bkhm_can_configure_features_per_specific_himpunan(): void
    {
        $bkhm = $this->createBkhmUser();
        $himaA = $this->createHimaUser('HIMA Informatika ITG', 'IF');
        $himaB = $this->createHimaUser('HIMASISFO ITG', 'SI');

        // Pastikan default global semua fitur aktif
        $this->assertTrue(HimaFiturSetting::isAktif('sarpras', $himaA));
        $this->assertTrue(HimaFiturSetting::isAktif('sarpras', $himaB));

        // BKHM mengatur HIMA A HANYA punya pengajuan dan lpj
        $response = $this->actingAs($bkhm)->post(route('bkhm.hima-fitur.update'), [
            'target' => (string)$himaA->id,
            'aktif_fitur' => ['pengajuan', 'lpj'],
        ]);

        $response->assertRedirect(route('bkhm.hima-fitur.index', ['target' => $himaA->id]));
        $response->assertSessionHas('success');

        // Verifikasi pengaturan HIMA A
        $this->assertTrue(HimaFiturSetting::isAktif('pengajuan', $himaA));
        $this->assertTrue(HimaFiturSetting::isAktif('lpj', $himaA));
        $this->assertFalse(HimaFiturSetting::isAktif('sarpras', $himaA));
        $this->assertFalse(HimaFiturSetting::isAktif('proker', $himaA));

        // HIMA B tetap mengikuti default global (semua aktif)
        $this->assertTrue(HimaFiturSetting::isAktif('sarpras', $himaB));
        $this->assertTrue(HimaFiturSetting::isAktif('proker', $himaB));
        $this->assertTrue(HimaFiturSetting::isAktif('pengajuan', $himaB));

        // Akses langsung rute oleh HIMA A:
        $this->actingAs($himaA)->get(route('pengajuan.index'))->assertStatus(200);
        $this->actingAs($himaA)->get(route('peminjaman.tempat.index'))->assertRedirect(route('dashboard'));

        // Akses langsung rute oleh HIMA B:
        $this->actingAs($himaB)->get(route('pengajuan.index'))->assertStatus(200);
        $this->actingAs($himaB)->get(route('peminjaman.tempat.index'))->assertStatus(200);
    }

    public function test_bkhm_can_reset_hima_to_default_settings(): void
    {
        $bkhm = $this->createBkhmUser();
        $hima = $this->createHimaUser();

        // Set kustom: hanya pengajuan
        HimaFiturSetting::setAktifForUser($hima->id, ['pengajuan']);
        $this->assertFalse(HimaFiturSetting::isAktif('sarpras', $hima));

        // Reset ke default
        $response = $this->actingAs($bkhm)->post(route('bkhm.hima-fitur.reset', $hima));
        $response->assertRedirect(route('bkhm.hima-fitur.index', ['target' => $hima->id]));
        $response->assertSessionHas('success');

        // Sarpras sekarang aktif lagi karena default global aktif
        $this->assertTrue(HimaFiturSetting::isAktif('sarpras', $hima));
        $this->assertFalse(HimaFiturSetting::hasCustomSettings($hima->id));
    }
}
