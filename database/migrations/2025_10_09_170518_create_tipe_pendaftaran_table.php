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
        Schema::create('tipe_pendaftaran', function (Blueprint $table) {
            $table->uuid('id_tipe_pendaftaran')->primary();
            $table->string('kode_tipe_pendaftaran', 10)->nullable(); // Added from missing columns migration
            $table->string('nama_tipe_pendaftaran');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->nullable(); // Added from missing columns migration
            $table->integer('prioritas')->default(1); // Added from missing columns migration
            $table->boolean('perlu_survei')->default(true); // Added from missing columns migration
            $table->boolean('otomatis_approve')->default(false); // Added from missing columns migration
            $table->decimal('data_pengembalian', 15, 2)->default(0); // nilai rupiah pengembalian
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_tipe_pendaftaran', 'status_aktif']);
        });

        // Add foreign key constraints to pendaftaran table after all master tables are created
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('cascade');
            $table->foreign('id_kelurahan')->references('id_kelurahan')->on('kelurahan')->onDelete('set null');
            $table->foreign('id_pekerjaan')->references('id_pekerjaan')->on('pekerjaan')->onDelete('set null');
            $table->foreign('id_tipe_layanan')->references('id_tipe_layanan')->on('tipe_layanan')->onDelete('set null');
            $table->foreign('id_jenis_daftar')->references('id_jenis_daftar')->on('jenis_daftar')->onDelete('set null');
            $table->foreign('id_tipe_pendaftaran')->references('id_tipe_pendaftaran')->on('tipe_pendaftaran')->onDelete('set null');
            // Note: id_pajak foreign key will be added after pajak table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys first
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['id_cabang']);
            $table->dropForeign(['id_kelurahan']);
            $table->dropForeign(['id_pekerjaan']);
            $table->dropForeign(['id_tipe_layanan']);
            $table->dropForeign(['id_jenis_daftar']);
            $table->dropForeign(['id_tipe_pendaftaran']);
            // Note: id_pajak foreign key will be dropped by pajak migration
        });

        Schema::dropIfExists('tipe_pendaftaran');
    }
};
