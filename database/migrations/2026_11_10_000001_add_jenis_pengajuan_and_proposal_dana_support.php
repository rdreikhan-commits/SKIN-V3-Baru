<?php

use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom jenis_pengajuan pada tabel pengajuan
        Schema::table('pengajuan', function (Blueprint $table) {
            if (! Schema::hasColumn('pengajuan', 'jenis_pengajuan')) {
                $table->string('jenis_pengajuan', 30)->default('kegiatan')->after('nama_kegiatan');
            }
        });

        // 2. Tambah kolom jenis_proposal pada tabel proposal_otomatis
        Schema::table('proposal_otomatis', function (Blueprint $table) {
            if (! Schema::hasColumn('proposal_otomatis', 'jenis_proposal')) {
                $table->string('jenis_proposal', 30)->default('kegiatan')->after('nama_kegiatan');
            }
        });

        // 3. Pastikan transisi bypass Satgas PPKS tersedia untuk BPM
        // Dari bem_approved langsung ke bpm_approved (BKHM)
        $bemApproved = WorkflowState::where('name', 'bem_approved')->first();
        $bpmApproved = WorkflowState::where('name', 'bpm_approved')->first();
        $draftState  = WorkflowState::where('name', 'draft')->first();

        if ($bemApproved && $bpmApproved) {
            WorkflowTransition::firstOrCreate([
                'from_state_id' => $bemApproved->id,
                'to_state_id'   => $bpmApproved->id,
                'required_role' => 'bpm',
            ], [
                'action_label'  => 'Setujui (Langsung ke BKHM)',
            ]);
        }

        if ($draftState && $bpmApproved) {
            WorkflowTransition::firstOrCreate([
                'from_state_id' => $draftState->id,
                'to_state_id'   => $bpmApproved->id,
                'required_role' => 'bpm',
            ], [
                'action_label'  => 'Ajukan ke BKHM',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan', 'jenis_pengajuan')) {
                $table->dropColumn('jenis_pengajuan');
            }
        });

        Schema::table('proposal_otomatis', function (Blueprint $table) {
            if (Schema::hasColumn('proposal_otomatis', 'jenis_proposal')) {
                $table->dropColumn('jenis_proposal');
            }
        });
    }
};

