<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_prioritas_pengaduan', function (Blueprint $table) {
            $table->uuid('id_prioritas_pengaduan')->primary();
            $table->string('kode_prioritas', 20)->unique();
            $table->string('nama_prioritas', 50);
            $table->integer('sla_jam')->default(24)->comment('Target waktu penanganan dalam jam');
            $table->string('warna_tampilan', 20)->default('gray')->comment('Warna tampilan di UI, misal: red, yellow, green');

            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_prioritas_pengaduan');
    }
};
