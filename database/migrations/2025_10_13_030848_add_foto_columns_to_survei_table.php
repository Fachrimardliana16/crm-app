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
            // Foto columns untuk dokumentasi survei
            $table->string('foto_peta_lokasi')->nullable()->after('rekomendasi');
            $table->string('foto_tanah_bangunan')->nullable()->after('foto_peta_lokasi');
            $table->string('foto_dinding')->nullable()->after('foto_tanah_bangunan');
            $table->string('foto_lantai')->nullable()->after('foto_dinding');
            $table->string('foto_atap')->nullable()->after('foto_lantai');
            $table->string('foto_pagar')->nullable()->after('foto_atap');
            $table->string('foto_jalan')->nullable()->after('foto_pagar');
            $table->string('foto_meteran_listrik')->nullable()->after('foto_jalan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            $table->dropColumn([
                'foto_peta_lokasi',
                'foto_tanah_bangunan',
                'foto_dinding',
                'foto_lantai',
                'foto_atap',
                'foto_pagar',
                'foto_jalan',
                'foto_meteran_listrik',
            ]);
        });
    }
};
