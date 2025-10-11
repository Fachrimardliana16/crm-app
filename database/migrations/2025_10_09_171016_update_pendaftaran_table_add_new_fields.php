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
            // Add nomor registrasi (generated from cabang code)
            $table->string('nomor_registrasi')->unique()->after('id_pendaftaran');

            // Add foreign keys for new master tables
            $table->uuid('id_cabang')->after('nomor_registrasi');
            $table->uuid('id_kelurahan')->nullable()->after('kelurahan_pemasangan');
            $table->uuid('id_pekerjaan')->nullable()->after('pekerjaan');
            $table->uuid('id_tipe_layanan')->nullable()->after('tipe_layanan');
            $table->uuid('id_jenis_daftar')->nullable()->after('jenis_daftar');
            $table->uuid('id_tipe_pendaftaran')->nullable()->after('tipe_daftar');

            // Remove NIK pemohon field
            $table->dropColumn('nik_pemohon');

            // Add foreign key constraints
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('cascade');
            $table->foreign('id_kelurahan')->references('id_kelurahan')->on('kelurahan')->onDelete('set null');
            $table->foreign('id_pekerjaan')->references('id_pekerjaan')->on('pekerjaan')->onDelete('set null');
            $table->foreign('id_tipe_layanan')->references('id_tipe_layanan')->on('tipe_layanan')->onDelete('set null');
            $table->foreign('id_jenis_daftar')->references('id_jenis_daftar')->on('jenis_daftar')->onDelete('set null');
            $table->foreign('id_tipe_pendaftaran')->references('id_tipe_pendaftaran')->on('tipe_pendaftaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['id_cabang']);
            $table->dropForeign(['id_kelurahan']);
            $table->dropForeign(['id_pekerjaan']);
            $table->dropForeign(['id_tipe_layanan']);
            $table->dropForeign(['id_jenis_daftar']);
            $table->dropForeign(['id_tipe_pendaftaran']);

            // Drop added columns
            $table->dropColumn([
                'nomor_registrasi',
                'id_cabang',
                'id_kelurahan',
                'id_pekerjaan',
                'id_tipe_layanan',
                'id_jenis_daftar',
                'id_tipe_pendaftaran',
                'status_pendaftaran'
            ]);

            // Add back NIK pemohon
            $table->string('nik_pemohon')->after('nama_pemohon');
        });
    }
};
