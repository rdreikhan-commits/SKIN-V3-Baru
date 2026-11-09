<?php

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
        Schema::table('program_kerjas', function (Blueprint $table) {
            if (! Schema::hasColumn('program_kerjas', 'status_prodi')) {
                $table->string('status_prodi', 20)->default('tidak_perlu')->after('status'); // tidak_perlu, menunggu, disetujui, ditolak
            }
            if (! Schema::hasColumn('program_kerjas', 'catatan_prodi')) {
                $table->text('catatan_prodi')->nullable()->after('catatan_bpm');
            }
            if (! Schema::hasColumn('program_kerjas', 'prodi_id')) {
                $table->foreignId('prodi_id')->nullable()->after('catatan_prodi')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('program_kerjas', 'disetujui_prodi_at')) {
                $table->timestamp('disetujui_prodi_at')->nullable()->after('prodi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kerjas', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->dropColumn([
                'status_prodi',
                'catatan_prodi',
                'prodi_id',
                'disetujui_prodi_at',
            ]);
        });
    }
};

