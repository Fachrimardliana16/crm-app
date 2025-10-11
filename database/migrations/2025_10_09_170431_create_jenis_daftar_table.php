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
        Schema::create('jenis_daftar', function (Blueprint $table) {
            $table->uuid('id_jenis_daftar')->primary();
            $table->string('nama_jenis_daftar');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya_daftar', 15, 2)->default(0);
            $table->decimal('biaya_layanan_tambahan', 15, 2)->default(0);
            $table->decimal('potongan_layanan', 15, 2)->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_jenis_daftar', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_daftar');
    }
};
