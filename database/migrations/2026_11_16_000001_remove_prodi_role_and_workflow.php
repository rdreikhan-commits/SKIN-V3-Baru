<?php

use App\Models\HistoriStatus;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Alihkan pengajuan & histori_status berstatus prodi_review ke submitted (jika ada)
        $prodiState = WorkflowState::where('name', 'prodi_review')->first();
        $submittedState = WorkflowState::where('name', 'submitted')->first();

        if ($prodiState) {
            if ($submittedState) {
                Pengajuan::where('workflow_state_id', $prodiState->id)
                    ->update(['workflow_state_id' => $submittedState->id]);

                Pengajuan::where('rejected_from_state_id', $prodiState->id)
                    ->update(['rejected_from_state_id' => $submittedState->id]);

                HistoriStatus::where('workflow_state_id', $prodiState->id)
                    ->update(['workflow_state_id' => $submittedState->id]);
            }

            // Hapus transisi terkait prodi_review atau required_role 'prodi'
            WorkflowTransition::where('from_state_id', $prodiState->id)
                ->orWhere('to_state_id', $prodiState->id)
                ->orWhere('required_role', 'prodi')
                ->delete();

            // Hapus state prodi_review
            $prodiState->delete();
        }

        // 2. Pastikan transisi draft -> submitted untuk role ormawa tersedia
        $draftState = WorkflowState::where('name', 'draft')->first();
        if ($draftState && $submittedState) {
            WorkflowTransition::firstOrCreate([
                'from_state_id' => $draftState->id,
                'to_state_id'   => $submittedState->id,
                'action_label'  => 'Ajukan',
                'required_role' => 'ormawa',
            ]);
        }

        // 3. Hapus pengguna dengan role prodi
        try {
            $prodiUsers = User::role('prodi')->get();
            foreach ($prodiUsers as $pUser) {
                $pUser->roles()->detach();
                $pUser->delete();
            }

            // Hapus role prodi dari Spatie Permission
            $prodiRole = Role::where('name', 'prodi')->first();
            if ($prodiRole) {
                $prodiRole->delete();
            }
        } catch (\Throwable $e) {
            // Abaikan jika role sudah tidak ada
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::firstOrCreate(['name' => 'prodi', 'guard_name' => 'web']);
    }
};
