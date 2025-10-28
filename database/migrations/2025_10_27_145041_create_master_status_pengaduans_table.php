<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_status_pengaduan', function (Blueprint $table) {
            $table->uuid('id_status_pengaduan')->primary();
            $table->string('kode_status', 20)->unique();
            $table->string('nama_status', 50);
            $table->string('warna_tampilan', 20)->default('gray')->comment('Warna tampilan di UI, misal: green, yellow, red');
            $table->boolean('status_aktif')->default(true)->comment('Menandakan apakah status masih digunakan');

            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_status_pengaduan');
    }
};
