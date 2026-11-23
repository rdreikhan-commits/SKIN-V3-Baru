<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\ProposalOtomatis;
use App\Models\TandaTanganDigital;
use App\Models\User;
use App\Services\DigitalSignatureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DigitalSignatureSecurityGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'KonfigurasiSeeder', '--force' => true]);
    }

    private function makeOrmawa(): User
    {
        $role = Role::firstOrCreate(['name' => 'ormawa']);
        $user = User::factory()->create([
            'name' => 'BEM Kema ITG',
            'nama_ketua' => 'Ahmad Fauzi',
            'nim_ketua' => '2206001',
            'nama_sekretaris' => 'Siti Nurhaliza',
            'nim_sekretaris' => '2206002',
            'jenis_ormawa' => 'ukm',
        ]);
        $user->assignRole($role);

        return $user;
    }

    /**
     * Dosen, Kaprodi, Dekan, WR3, dan Rektor TIDAK BOLEH dibuatkan TTD digital kriptografis (QR)
     * otomatis oleh mahasiswa untuk mencegah penyalahgunaan wewenang TTD.
     */
    public function test_dosen_dan_pejabat_kampus_tidak_mendapat_ttd_digital_qr()
    {
        $ormawa = $this->makeOrmawa();
        $this->actingAs($ormawa);

        $payload = [
            'type' => 'undangan',
            'perihal' => 'Undangan Rapat Koordinasi Akbar',
            'tujuan' => 'Yth. Pimpinan dan Mitra Kampus',
            'kalimat_pembuka' => 'Dengan hormat, kami bermaksud mengundang...',
            'nama_acara' => 'Rapat Akbar',
            'hari_tanggal' => 'Senin, 10 Maret 2026',
            'waktu' => '09.00 WIB',
            'tempat' => 'Aula Utama ITG',
            // Kirim input campuran: Mahasiswa dan Pejabat/Dosen
            'penandatangan_jenis' => ['internal', 'internal', 'internal'],
            'penandatangan_role' => ['ketua', 'pembina', 'dekan'],
            'penandatangan_nama' => ['Ahmad Fauzi', 'Dr. Irwan Setiawan, M.T.', 'Prof. Dr. Hilman, M.Sc.'],
            'penandatangan_jabatan' => ['Ketua Umum Ormawa', 'Dosen Pembina Ormawa', 'Dekan Fakultas Teknik'],
            'penandatangan_nim' => ['2206001', '0412088001', '197001011995031001'],
            'penandatangan_label_id' => ['NIM', 'NIDN', 'NIP'],
        ];

        $response = $this->post(route('generator.letters.store'), $payload);
        $response->assertRedirect();

        $letter = Letter::latest()->first();
        $this->assertNotNull($letter);

        // Periksa tanda tangan digital kriptografis yang tersimpan
        $sigs = TandaTanganDigital::where('signable_type', Letter::class)
            ->where('signable_id', $letter->id)
            ->get();

        // HANYA mahasiswa (Ketua Umum ber-NIM) yang mendapat TTD digital QR!
        $this->assertCount(1, $sigs);
        $this->assertEquals('Ahmad Fauzi', $sigs->first()->nama_penandatangan);

        // Dosen Pembina dan Dekan TIDAK ada di tabel tanda tangan digital kriptografis
        $this->assertFalse($sigs->contains('nama_penandatangan', 'Dr. Irwan Setiawan, M.T.'));
        $this->assertFalse($sigs->contains('nama_penandatangan', 'Prof. Dr. Hilman, M.Sc.'));

        // Namun pada tampilan surat, Dosen dan Dekan tetap dicantumkan sebagai penandatangan manual
        $show = $this->get(route('generator.letters.show', $letter));
        $show->assertStatus(200);
        $show->assertSee('Ahmad Fauzi');
        $show->assertSee('Dr. Irwan Setiawan, M.T.');
        $show->assertSee('Prof. Dr. Hilman, M.Sc.');
    }

    /**
     * Uji langsung DigitalSignatureService::signMany menolak dosen, dekan, rektor, wr3
     */
    public function test_service_sign_many_menolak_pejabat_meskipun_jenis_internal()
    {
        $ormawa = $this->makeOrmawa();

        $letter = Letter::create([
            'user_id' => $ormawa->id,
            'type' => 'undangan',
            'perihal' => 'Uji Proteksi Keamanan TTD',
            'content' => 'Isi surat uji coba',
            'metadata' => [],
        ]);

        $signers = [
            ['jenis' => 'internal', 'role' => 'ketua', 'nama' => 'Ahmad Fauzi', 'jabatan' => 'Ketua Umum', 'nim' => '2206001'],
            ['jenis' => 'internal', 'role' => 'rektor', 'nama' => 'Rektor ITG', 'jabatan' => 'Rektor Institut Teknologi Garut', 'nidn' => '0401017001'],
            ['jenis' => 'internal', 'role' => 'wr3', 'nama' => 'Wakil Rektor 3', 'jabatan' => 'Wakil Rektor III', 'nidn' => '0402027501'],
            ['jenis' => 'internal', 'role' => 'dosen', 'nama' => 'Dosen Pembimbing, M.Kom.', 'jabatan' => 'Dosen Pembimbing', 'nidn' => '0403038001'],
            ['jenis' => 'eksternal', 'role' => 'mitra', 'nama' => 'Pimpinan PT Mitra', 'jabatan' => 'Direktur Utama', 'nim' => '320500123'],
        ];

        $signedMap = DigitalSignatureService::signMany($letter, $signers, $ormawa);

        // Hanya index 0 (Ahmad Fauzi) yang berhak mendapat TTD digital QR
        $this->assertCount(1, $signedMap);
        $this->assertArrayHasKey(0, $signedMap);
        $this->assertArrayNotHasKey(1, $signedMap);
        $this->assertArrayNotHasKey(2, $signedMap);
        $this->assertArrayNotHasKey(3, $signedMap);
        $this->assertArrayNotHasKey(4, $signedMap);

        $this->assertEquals('Ahmad Fauzi', $signedMap[0]->nama_penandatangan);
    }
}
