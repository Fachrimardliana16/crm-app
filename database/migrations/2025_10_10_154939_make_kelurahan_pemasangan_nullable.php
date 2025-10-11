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
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Make these string fields nullable since we use foreign key references
            $table->string('kelurahan_pemasangan')->nullable()->change();
            $table->string('cabang_pendaftaran')->nullable()->change();
            $table->string('tipe_layanan')->nullable()->change();
            $table->string('pekerjaan_pemohon')->nullable()->change();
            $table->string('jenis_daftar')->nullable()->change();
            $table->string('tipe_daftar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Revert back to NOT NULL
            $table->string('kelurahan_pemasangan')->nullable(false)->change();
            $table->string('cabang_pendaftaran')->nullable(false)->change();
            $table->string('tipe_layanan')->nullable(false)->change();
            $table->string('pekerjaan_pemohon')->nullable(false)->change();
            $table->string('jenis_daftar')->nullable(false)->change();
            $table->string('tipe_daftar')->nullable(false)->change();
        });
    }
};
