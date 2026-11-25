<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan konfigurasi fitur yang boleh tampil untuk HIMA.
     * Dikelola oleh BKHM melalui halaman Pengaturan Fitur Himpunan.
     */
    public function up(): void
    {
        Schema::create('hima_fitur_settings', function (Blueprint $table) {
            $table->id();
            // Key fitur, contoh: 'pengajuan', 'lpj', 'sarpras', 'persuratan', 'proker', 'prestasi'
            $table->string('fitur_key')->unique();
            // Label tampil untuk BKHM
            $table->string('fitur_label');
            // Deskripsi singkat fitur
            $table->string('deskripsi')->nullable();
            // Apakah fitur ini aktif / muncul di sidebar HIMA
            $table->boolean('is_aktif')->default(true);
            // Urutan tampil di sidebar
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hima_fitur_settings');
    }
};
