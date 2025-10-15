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
        Schema::create('status', function (Blueprint $table) {
            $table->uuid('id_status')->primary();
            $table->string('tabel_referensi', 50); // Referensi ke tabel mana status ini digunakan
            $table->string('kode_status', 50); // Kode unik status
            $table->string('nama_status'); // Nama display status
            $table->text('deskripsi_status')->nullable(); // Deskripsi status
            $table->string('warna_status', 20)->nullable(); // Warna untuk badge/display
            $table->integer('urutan_tampil')->default(1); // Urutan tampilan
            $table->boolean('status_aktif')->default(true); // Apakah status aktif
            $table->text('keterangan')->nullable(); // Keterangan tambahan

            // Audit fields
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            // Indexes
            $table->index(['tabel_referensi', 'kode_status']);
            $table->index(['tabel_referensi', 'urutan_tampil']);
            $table->index('status_aktif');

            // Unique constraint
            $table->unique(['tabel_referensi', 'kode_status'], 'unique_tabel_kode_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
