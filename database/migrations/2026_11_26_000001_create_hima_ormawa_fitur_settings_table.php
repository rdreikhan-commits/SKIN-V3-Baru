<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan pengaturan fitur per-Himpunan spesifik.
     * Jika sebuah Himpunan belum ada entri di sini, maka otomatis mengikuti hima_fitur_settings (global default).
     */
    public function up(): void
    {
        Schema::create('hima_ormawa_fitur_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('fitur_key');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'fitur_key']);
            $table->index(['user_id', 'is_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hima_ormawa_fitur_settings');
    }
};
