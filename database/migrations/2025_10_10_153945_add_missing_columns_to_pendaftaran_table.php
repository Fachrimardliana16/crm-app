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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('pendaftaran', 'no_hp_pemohon')) {
                $table->string('no_hp_pemohon', 20)->nullable()->after('alamat_pemasangan');
            }

            if (!Schema::hasColumn('pendaftaran', 'elevasi_awal_mdpl')) {
                $table->decimal('elevasi_awal_mdpl', 8, 2)->nullable()->after('longitude_awal');
            }

            if (!Schema::hasColumn('pendaftaran', 'keterangan_arah_lokasi')) {
                $table->text('keterangan_arah_lokasi')->nullable()->after('elevasi_awal_mdpl');
            }

            if (!Schema::hasColumn('pendaftaran', 'scan_identitas_utama')) {
                $table->string('scan_identitas_utama')->nullable()->after('keterangan_arah_lokasi');
            }

            if (!Schema::hasColumn('pendaftaran', 'scan_dokumen_mou')) {
                $table->string('scan_dokumen_mou')->nullable()->after('scan_identitas_utama');
            }

            if (!Schema::hasColumn('pendaftaran', 'ada_toren')) {
                $table->boolean('ada_toren')->default(false)->after('scan_dokumen_mou');
            }

            if (!Schema::hasColumn('pendaftaran', 'ada_sumur')) {
                $table->boolean('ada_sumur')->default(false)->after('ada_toren');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'no_hp_pemohon',
                'elevasi_awal_mdpl',
                'keterangan_arah_lokasi',
                'scan_identitas_utama',
                'scan_dokumen_mou',
                'ada_toren',
                'ada_sumur'
            ]);
        });
    }
};
