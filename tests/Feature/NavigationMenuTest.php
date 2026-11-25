<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class NavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'WorkflowSeeder', '--force' => true]);
    }

    private function createUserWithRole(string $roleName): User
    {
        $user = User::factory()->create(['saldo' => 10000000]);
        $user->assignRole($roleName);

        return $user;
    }

    public function test_bem_can_see_verifikasi_and_proker_menu(): void
    {
        $bem = $this->createUserWithRole('bem');

        $response = $this->actingAs($bem)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('verifikasi.index'));
        $response->assertSee(route('proker.index'));
        $response->assertSee(route('pengajuan.create'));
    }

    public function test_bpm_can_see_verifikasi_and_aspirasi_menu(): void
    {
        $bpm = $this->createUserWithRole('bpm');

        $response = $this->actingAs($bpm)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('verifikasi.index'));
        $response->assertSee(route('bpm.aspirasi.index'));
        $response->assertSee(route('pengajuan.create'));
    }

    public function test_bkhm_can_see_verifikasi_and_layanan_menu(): void
    {
        $bkhm = $this->createUserWithRole('bkhm');

        $response = $this->actingAs($bkhm)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('verifikasi.index'));
        $response->assertSee(route('bkhm.kurasi.index'));
        $response->assertSee(route('bkhm.konseling.index'));
        $response->assertSee(route('bkhm.tiket-aspirasi.index'));
        $response->assertSee(route('bkhm.export.excel'));
    }

    public function test_wr3_can_see_verifikasi_and_prestasi_menu(): void
    {
        $wr3 = $this->createUserWithRole('wr3');

        $response = $this->actingAs($wr3)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('verifikasi.index'));
        $response->assertSee(route('prestasi.index'));
    }

    public function test_bendahara_can_see_pencairan_and_export_menu(): void
    {
        $bendahara = $this->createUserWithRole('bendahara');

        $response = $this->actingAs($bendahara)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('verifikasi.index'));
        $response->assertSee(route('bendahara.export.excel'));
        $response->assertSee(route('bendahara.export.pdf'));
    }

    public function test_ormawa_ukm_can_see_prestasi_and_sarpras_menu(): void
    {
        $ormawa = $this->createUserWithRole('ormawa');
        $ormawa->update(['jenis_ormawa' => 'ukm', 'name' => 'UKM Seni ITG']);

        $response = $this->actingAs($ormawa)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('pengajuan.create'));
        $response->assertSee(route('prestasi.index'));
        $response->assertSee(route('peminjaman.tempat.create'));
        $response->assertSee(route('proker.index'));
    }

    public function test_hima_navigation_only_shows_dana_non_akademik_and_lpj(): void
    {
        // Simulasikan ketika BKHM menonaktifkan fitur sarpras, proker, persuratan, dan prestasi untuk HIMA
        \App\Models\HimaFiturSetting::create(['fitur_key' => 'sarpras', 'fitur_label' => 'Sarpras', 'is_aktif' => false]);
        \App\Models\HimaFiturSetting::create(['fitur_key' => 'proker', 'fitur_label' => 'Proker', 'is_aktif' => false]);
        \App\Models\HimaFiturSetting::create(['fitur_key' => 'persuratan', 'fitur_label' => 'Persuratan', 'is_aktif' => false]);
        \App\Models\HimaFiturSetting::create(['fitur_key' => 'prestasi', 'fitur_label' => 'Prestasi', 'is_aktif' => false]);
        \App\Models\HimaFiturSetting::create(['fitur_key' => 'pengajuan', 'fitur_label' => 'Pengajuan', 'is_aktif' => true]);
        \App\Models\HimaFiturSetting::create(['fitur_key' => 'lpj', 'fitur_label' => 'LPJ', 'is_aktif' => true]);
        \App\Models\HimaFiturSetting::clearCache();

        $hima = $this->createUserWithRole('ormawa');
        $hima->update(['jenis_ormawa' => 'hima', 'name' => 'HIMA Informatika']);

        $response = $this->actingAs($hima)->get(route('dashboard'));

        $response->assertStatus(200);
        // HIMA melihat menu Dana Non-Akademik & LPJ
        $response->assertSee(route('pengajuan.create'));
        $response->assertSee(route('pengajuan.index'));
        $response->assertSee(route('generator.lpj.create'));
        $response->assertSee(route('lpj.index'));

        // HIMA TIDAK melihat menu Sarpras, Proker, Persuratan Lain, Prestasi saat dinonaktifkan BKHM
        $response->assertDontSee(route('peminjaman.tempat.create'));
        $response->assertDontSee(route('peminjaman.barang.create'));
        $response->assertDontSee(route('proker.index'));
        $response->assertDontSee(route('generator.create'));
        $response->assertDontSee(route('generator.letters.create'));
        $response->assertDontSee(route('prestasi.index'));
    }
}
