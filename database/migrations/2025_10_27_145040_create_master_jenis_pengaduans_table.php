<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_jenis_pengaduan', function (Blueprint $table) {
            $table->uuid('id_jenis_pengaduan')->primary();
            $table->string('kode_jenis', 20)->unique();
            $table->string('nama_jenis', 100);
            $table->text('deskripsi')->nullable();

            // relasi otomatis ke prioritas
            $table->uuid('id_prioritas_pengaduan')->nullable()->index()
                ->comment('Prioritas default dari jenis pengaduan');

            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_jenis_pengaduan');
    }
};
