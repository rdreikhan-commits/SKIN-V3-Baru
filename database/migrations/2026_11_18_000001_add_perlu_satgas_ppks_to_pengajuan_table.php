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
        Schema::table('pengajuan', function (Blueprint $table) {
            if (! Schema::hasColumn('pengajuan', 'perlu_satgas_ppks')) {
                $table->boolean('perlu_satgas_ppks')->default(false)->after('jenis_pengajuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan', 'perlu_satgas_ppks')) {
                $table->dropColumn('perlu_satgas_ppks');
            }
        });
    }
};
