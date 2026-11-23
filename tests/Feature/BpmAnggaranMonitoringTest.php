<?php

namespace Tests\Feature;

use App\Models\Dana;
use App\Models\Pengajuan;
use App\Models\SaldoHistori;
use App\Models\User;
use App\Models\WorkflowState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BpmAnggaranMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'KonfigurasiSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'WorkflowSeeder', '--force' => true]);
    }

    private function makeUserWithRole(string $roleName, array $attributes = []): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        $user = User::factory()->create(array_merge([
            'saldo' => 10000000,
            'saldo_awal' => 10000000,
        ], $attributes));
        $user->assignRole($role);

        return $user;
    }

    public function test_bpm_dapat_melihat_halaman_monitoring_semua_anggaran_organisasi()
    {
        $bpm = $this->makeUserWithRole('bpm', ['name' => 'BPM ITG']);
        $hima = $this->makeUserWithRole('ormawa', [
            'name' => 'HIMA Informatika',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Informatika',
            'saldo_awal' => 15000000,
            'saldo' => 10000000,
        ]);
        $ukm = $this->makeUserWithRole('ormawa', [
            'name' => 'UKM Musik ITG',
            'jenis_ormawa' => 'ukm',
            'saldo_awal' => 8000000,
            'saldo' => 6000000,
        ]);
        $bem = $this->makeUserWithRole('bem', [
            'name' => 'BEM ITG',
            'saldo_awal' => 20000000,
            'saldo' => 15000000,
        ]);

        $this->actingAs($bpm);

        $response = $this->get(route('bpm.anggaran.index'));
        $response->assertStatus(200);

        // Periksa nama-nama organisasi muncul di tabel
        $response->assertSee('HIMA Informatika');
        $response->assertSee('UKM Musik ITG');
        $response->assertSee('BEM ITG');
        $response->assertSee('BPM ITG');

        // Periksa metrik global muncul
        // Total Pagu = 15jt + 8jt + 20jt + 10jt = 53jt
        $response->assertSee('Rp 53.000.000');
    }

    public function test_filter_kategori_anggaran_hima_dan_ukm()
    {
        $bpm = $this->makeUserWithRole('bpm');
        $hima = $this->makeUserWithRole('ormawa', [
            'name' => 'HIMA Sipil ITG',
            'jenis_ormawa' => 'hima',
            'prodi' => 'Teknik Sipil',
        ]);
        $ukm = $this->makeUserWithRole('ormawa', [
            'name' => 'UKM Robotika ITG',
            'jenis_ormawa' => 'ukm',
        ]);

        $this->actingAs($bpm);

        // Filter HIMA
        $responseHima = $this->get(route('bpm.anggaran.index', ['kategori' => 'hima']));
        $responseHima->assertStatus(200);
        $responseHima->assertSee('HIMA Sipil ITG');
        $responseHima->assertDontSee('UKM Robotika ITG');

        // Filter UKM
        $responseUkm = $this->get(route('bpm.anggaran.index', ['kategori' => 'ukm']));
        $responseUkm->assertStatus(200);
        $responseUkm->assertSee('UKM Robotika ITG');
        $responseUkm->assertDontSee('HIMA Sipil ITG');
    }

    public function test_pencarian_anggaran_organisasi()
    {
        $bpm = $this->makeUserWithRole('bpm');
        $hima1 = $this->makeUserWithRole('ormawa', ['name' => 'HIMA Industri ITG', 'prodi' => 'Teknik Industri']);
        $hima2 = $this->makeUserWithRole('ormawa', ['name' => 'HIMA Elektro ITG', 'prodi' => 'Teknik Elektro']);

        $this->actingAs($bpm);

        $response = $this->get(route('bpm.anggaran.index', ['q' => 'Elektro']));
        $response->assertStatus(200);
        $response->assertSee('HIMA Elektro ITG');
        $response->assertDontSee('HIMA Industri ITG');
    }

    public function test_bpm_dapat_melihat_detail_anggaran_satu_organisasi()
    {
        $bpm = $this->makeUserWithRole('bpm');
        $ormawa = $this->makeUserWithRole('ormawa', [
            'name' => 'HIMA Arsitektur ITG',
            'saldo_awal' => 12000000,
            'saldo' => 9000000,
            'nama_ketua' => 'Budi Santoso',
            'nim_ketua' => '2207001',
        ]);

        $state = WorkflowState::where('name', WorkflowState::FUNDS_DISBURSED)->first()
            ?: WorkflowState::factory()->create(['name' => WorkflowState::FUNDS_DISBURSED]);

        // Buat satu pengajuan proposal
        $pengajuan = Pengajuan::create([
            'user_id' => $ormawa->id,
            'nama_kegiatan' => 'Sayembara Desain Gedung Hijau 2026',
            'jenis_pengajuan' => 'kegiatan',
            'dana_diajukan' => 3000000,
            'tanggal_pengajuan' => now()->toDateString(),
            'workflow_state_id' => $state->id,
        ]);

        // Buat pencairan dana
        Dana::create([
            'pengajuan_id' => $pengajuan->id,
            'nominal_cair' => 3000000,
            'tanggal_cair' => now(),
            'keterangan' => 'Pencairan 100%',
        ]);

        // Buat saldo histori
        SaldoHistori::create([
            'user_id' => $ormawa->id,
            'actor_id' => $bpm->id,
            'tipe' => 'kurang',
            'nominal_sebelum' => 12000000,
            'nominal_sesudah' => 9000000,
            'selisih' => -3000000,
            'catatan' => 'Pencairan dana kegiatan sayembara',
        ]);

        $this->actingAs($bpm);

        $response = $this->get(route('bpm.anggaran.show', $ormawa));
        $response->assertStatus(200);
        $response->assertSee('Detail Anggaran: HIMA Arsitektur ITG');
        $response->assertSee('Rp 12.000.000');
        $response->assertSee('Rp 3.000.000');
        $response->assertSee('Rp 9.000.000');
        $response->assertSee('Sayembara Desain Gedung Hijau 2026');
        $response->assertSee('Pencairan dana kegiatan sayembara');
    }

    public function test_pengguna_biasa_tidak_bisa_mengakses_fitur_anggaran_bpm()
    {
        $ormawa = $this->makeUserWithRole('ormawa');
        $this->actingAs($ormawa);

        $response = $this->get(route('bpm.anggaran.index'));
        $response->assertStatus(403);
    }

    public function test_dashboard_bpm_menampilkan_widget_pengawasan_anggaran()
    {
        $bpm = $this->makeUserWithRole('bpm');
        $this->makeUserWithRole('ormawa', ['name' => 'UKM Pecinta Alam', 'saldo_awal' => 10000000, 'saldo' => 8000000]);

        $this->actingAs($bpm);

        $response = $this->get(route('bpm.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Pengawasan Anggaran Seluruh Organisasi Mahasiswa');
        $response->assertSee('Total Pagu Kampus ITG');
        $response->assertSee('UKM Pecinta Alam');
        $response->assertSee(route('bpm.anggaran.index'));
    }
}
