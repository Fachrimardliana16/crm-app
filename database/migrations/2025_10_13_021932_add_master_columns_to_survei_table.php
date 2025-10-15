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
            // Parameter Luas (dengan range sebagai enum)
            $table->enum('luas_tanah', ['0-60', '60-120', '120-200', '200-300', '>300'])->nullable();
            $table->enum('luas_bangunan', ['0-36', '36-70', '70-120', '120-200', '>200'])->nullable();

            // Parameter Lokasi & Material Bangunan
            $table->enum('lokasi_bangunan', ['gang-sempit', 'gang-sedang', 'tepi-jalan-kecil', 'tepi-jalan-besar', 'jalan-utama'])->nullable();
            $table->enum('dinding_bangunan', ['bambu-kayu', 'semi-permanen', 'tembok-setengah', 'tembok-penuh', 'bata-expose'])->nullable();
            $table->enum('lantai_bangunan', ['tanah', 'semen', 'keramik-biasa', 'keramik-bagus', 'granit-marmer'])->nullable();
            $table->enum('atap_bangunan', ['rumbia-jerami', 'seng-asbes', 'genteng-tanah', 'genteng-beton', 'dak-beton'])->nullable();
            $table->enum('pagar_bangunan', ['tidak-ada', 'bambu-kayu', 'kawat-seng', 'tembok-setengah', 'tembok-penuh'])->nullable();
            $table->enum('lokasi_jalan', ['tanah-berbatu', 'makadam', 'paving-conblock', 'aspal-sedang', 'aspal-mulus'])->nullable();

            // Parameter Listrik & Sosial Ekonomi
            $table->enum('daya_listrik', ['non-pln', '450-900', '1300', '2200', '>2200'])->nullable();
            $table->enum('fungsi_rumah', ['kontrak-kost', 'rumah-sendiri', 'rumah-keluarga', 'rumah-dinas', 'rumah-mewah'])->nullable();
            $table->enum('kepemilikan_kendaraan', ['tidak-ada', 'sepeda-becak', 'sepeda-motor', 'mobil-motor', 'mobil-mewah'])->nullable();

            // Hasil Survei
            $table->integer('nilai_survei')->nullable();
            $table->enum('golongan_survei', ['A', 'B', 'C', 'D'])->nullable();
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
                'nilai_survei',
                'golongan_survei'
            ]);
        });
    }
};
