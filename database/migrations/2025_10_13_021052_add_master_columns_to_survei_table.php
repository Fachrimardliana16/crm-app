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
        Schema::table('survei', function (Blueprint $table) {
            // Master parameter kolom
            $table->string('luas_tanah')->nullable()->after('jarak_pemasangan');
            $table->string('luas_bangunan')->nullable()->after('luas_tanah');
            $table->string('lokasi_bangunan')->nullable()->after('luas_bangunan');
            $table->string('dinding_bangunan')->nullable()->after('lokasi_bangunan');
            $table->string('lantai_bangunan')->nullable()->after('dinding_bangunan');
            $table->string('atap_bangunan')->nullable()->after('lantai_bangunan');
            $table->string('pagar_bangunan')->nullable()->after('atap_bangunan');
            $table->string('lokasi_jalan')->nullable()->after('pagar_bangunan');
            $table->string('daya_listrik')->nullable()->after('lokasi_jalan');
            $table->string('fungsi_rumah')->nullable()->after('daya_listrik');
            $table->string('kepemilikan_kendaraan')->nullable()->after('fungsi_rumah');

            // Field untuk map picker
            $table->json('lokasi_map')->nullable()->after('kepemilikan_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            $table->dropColumn([
                'luas_tanah',
                'luas_bangunan',
                'lokasi_bangunan',
                'dinding_bangunan',
                'lantai_bangunan',
                'atap_bangunan',
                'pagar_bangunan',
                'lokasi_jalan',
                'daya_listrik',
                'fungsi_rumah',
                'kepemilikan_kendaraan',
                'lokasi_map',
            ]);
        });
    }
};
