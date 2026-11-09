<?php

use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah roles baru pada Spatie Permission
        foreach (['prodi', 'satgas_ppks'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Modifikasi tabel users
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'jenis_ormawa')) {
                $table->string('jenis_ormawa', 20)->nullable()->after('status_akun'); // hima, ukm, bem, bpm
            }
            if (! Schema::hasColumn('users', 'kode_prodi')) {
                $table->string('kode_prodi', 10)->nullable()->after('prodi'); // IF, SI, TS, TI, AR
            }
        });

        // 3. Modifikasi tabel pengajuan untuk menampung instrumen PPKS & Prodi
        Schema::table('pengajuan', function (Blueprint $table) {
            if (! Schema::hasColumn('pengajuan', 'file_pakta_ppks')) {
                $table->string('file_pakta_ppks')->nullable()->after('file_proposal');
            }
            if (! Schema::hasColumn('pengajuan', 'kategori_kegiatan')) {
                $table->string('kategori_kegiatan', 30)->default('dalam_kampus')->after('file_pakta_ppks'); // dalam_kampus, luar_kampus, menginap
            }
            if (! Schema::hasColumn('pengajuan', 'kontak_pic_ppks')) {
                $table->string('kontak_pic_ppks', 100)->nullable()->after('kategori_kegiatan');
            }
            if (! Schema::hasColumn('pengajuan', 'catatan_ppks')) {
                $table->text('catatan_ppks')->nullable()->after('catatan_revisi');
            }
            if (! Schema::hasColumn('pengajuan', 'catatan_prodi')) {
                $table->text('catatan_prodi')->nullable()->after('catatan_ppks');
            }
            if (! Schema::hasColumn('pengajuan', 'verified_prodi_at')) {
                $table->timestamp('verified_prodi_at')->nullable()->after('catatan_prodi');
            }
            if (! Schema::hasColumn('pengajuan', 'verified_ppks_at')) {
                $table->timestamp('verified_ppks_at')->nullable()->after('verified_prodi_at');
            }
        });

        // 4. Inisialisasi States Baru di workflow_states
        $states = [
            ['name' => 'draft', 'label' => 'Draft', 'order_num' => 1, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Ketua Ormawa / Seskab'],
            ['name' => 'prodi_review', 'label' => 'Verifikasi Pembina Prodi', 'order_num' => 2, 'pic_role' => 'Pembina Himpunan / Kaprodi', 'pic_contact' => 'Ketua Program Studi'],
            ['name' => 'submitted', 'label' => 'Diajukan ke BEM', 'order_num' => 3, 'pic_role' => 'Pengurus BEM', 'pic_contact' => 'Divisi Administrasi BEM'],
            ['name' => 'bem_approved', 'label' => 'Verifikasi BPM', 'order_num' => 4, 'pic_role' => 'Pengurus BPM', 'pic_contact' => 'Komisi Pengawasan BPM'],
            ['name' => 'ppks_review', 'label' => 'Verifikasi Satgas PPKS', 'order_num' => 5, 'pic_role' => 'Satgas PPKS ITG', 'pic_contact' => 'Satgas PPKS ITG'],
            ['name' => 'bpm_approved', 'label' => 'Verifikasi BKHM (Tahap 1)', 'order_num' => 6, 'pic_role' => 'BKHM', 'pic_contact' => 'Bapak Encep (BKHM)'],
            ['name' => 'bkhm_approved', 'label' => 'Verifikasi WR3', 'order_num' => 7, 'pic_role' => 'Wakil Rektor III', 'pic_contact' => 'Kantor WR III'],
            ['name' => 'wr3_approved', 'label' => 'Disetujui WR3 - Siap Diajukan ke Bendahara', 'order_num' => 8, 'pic_role' => 'BKHM (Tahap 2) / Bendahara', 'pic_contact' => 'Bagian Keuangan'],
            ['name' => 'to_treasurer', 'label' => 'Diajukan ke Bendahara', 'order_num' => 9, 'pic_role' => 'Bendahara Kampus', 'pic_contact' => 'Bagian Keuangan/Bendahara'],
            ['name' => 'funds_disbursed', 'label' => 'Dana Cair', 'order_num' => 10, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Pelaksana Kegiatan'],
            ['name' => 'lpj_submitted', 'label' => 'LPJ Diajukan', 'order_num' => 11, 'pic_role' => 'BKHM', 'pic_contact' => 'Bapak Encep (BKHM)'],
            ['name' => 'lpj_wr3_review', 'label' => 'Verifikasi LPJ WR3', 'order_num' => 12, 'pic_role' => 'Wakil Rektor III', 'pic_contact' => 'Kantor WR III'],
            ['name' => 'completed', 'label' => 'Selesai', 'order_num' => 13, 'pic_role' => 'Sistem / Arsip', 'pic_contact' => 'Arsip Digital SKIN'],
            ['name' => 'cancelled', 'label' => 'Dibatalkan Pengaju', 'order_num' => 98, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Dibatalkan oleh Pengaju'],
            ['name' => 'rejected', 'label' => 'Ditolak', 'order_num' => 99, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Lihat Catatan Kendala'],
        ];

        foreach ($states as $state) {
            WorkflowState::updateOrCreate(['name' => $state['name']], $state);
        }

        // 5. Inisialisasi Transitions Baru
        $newTransitions = [
            // Alur HIMA ke Prodi
            ['from' => 'draft', 'to' => 'prodi_review', 'label' => 'Ajukan ke Pembina Prodi', 'role' => 'ormawa'],
            ['from' => 'prodi_review', 'to' => 'submitted', 'label' => 'Setujui', 'role' => 'prodi'],
            ['from' => 'prodi_review', 'to' => 'draft', 'label' => 'Revisi', 'role' => 'prodi'],
            ['from' => 'prodi_review', 'to' => 'rejected', 'label' => 'Tolak', 'role' => 'prodi'],

            // BPM meneruskan ke Satgas PPKS (bukan langsung ke BKHM)
            ['from' => 'bem_approved', 'to' => 'ppks_review', 'label' => 'Setujui', 'role' => 'bpm'],

            // Pengaju BPM langsung ke Satgas PPKS
            ['from' => 'draft', 'to' => 'ppks_review', 'label' => 'Ajukan ke Satgas PPKS', 'role' => 'bpm'],

            // Satgas PPKS verifikasi dokumen izin & kepatuhan
            ['from' => 'ppks_review', 'to' => 'bpm_approved', 'label' => 'Setujui Dokumen Izin', 'role' => 'satgas_ppks'],
            ['from' => 'ppks_review', 'to' => 'draft', 'label' => 'Revisi Dokumen Mitigasi', 'role' => 'satgas_ppks'],
            ['from' => 'ppks_review', 'to' => 'rejected', 'label' => 'Tolak Dokumen Izin', 'role' => 'satgas_ppks'],
        ];

        foreach ($newTransitions as $t) {
            $from = WorkflowState::where('name', $t['from'])->first();
            $to = WorkflowState::where('name', $t['to'])->first();

            if ($from && $to) {
                WorkflowTransition::firstOrCreate([
                    'from_state_id' => $from->id,
                    'to_state_id'   => $to->id,
                    'action_label'  => $t['label'],
                    'required_role' => $t['role'],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn([
                'file_pakta_ppks',
                'kategori_kegiatan',
                'kontak_pic_ppks',
                'catatan_ppks',
                'catatan_prodi',
                'verified_prodi_at',
                'verified_ppks_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jenis_ormawa', 'kode_prodi']);
        });
    }
};

