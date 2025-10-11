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
        // Update tipe_layanan table
        Schema::table('tipe_layanan', function (Blueprint $table) {
            $table->string('kode_tipe_layanan', 10)->nullable()->after('id_tipe_layanan');
            $table->decimal('biaya_standar', 15, 2)->nullable()->after('deskripsi');
        });

        // Update jenis_daftar table
        Schema::table('jenis_daftar', function (Blueprint $table) {
            $table->string('kode_jenis_daftar', 10)->nullable()->after('id_jenis_daftar');
            $table->decimal('biaya_tambahan', 15, 2)->nullable()->after('deskripsi');
            $table->integer('lama_proses_hari')->nullable()->after('biaya_tambahan');
        });

        // Update tipe_pendaftaran table
        Schema::table('tipe_pendaftaran', function (Blueprint $table) {
            $table->string('kode_tipe_pendaftaran', 10)->nullable()->after('id_tipe_pendaftaran');
            $table->decimal('biaya_admin', 15, 2)->nullable()->after('deskripsi');
            $table->integer('prioritas')->default(1)->after('biaya_admin');
            $table->boolean('perlu_survei')->default(true)->after('prioritas');
            $table->boolean('otomatis_approve')->default(false)->after('perlu_survei');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipe_layanan', function (Blueprint $table) {
            $table->dropColumn(['kode_tipe_layanan', 'biaya_standar']);
        });

        Schema::table('jenis_daftar', function (Blueprint $table) {
            $table->dropColumn(['kode_jenis_daftar', 'biaya_tambahan', 'lama_proses_hari']);
        });

        Schema::table('tipe_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['kode_tipe_pendaftaran', 'biaya_admin', 'prioritas', 'perlu_survei', 'otomatis_approve']);
        });
    }
};
