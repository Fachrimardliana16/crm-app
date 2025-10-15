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
            // Drop existing enum columns if they exist
            $columns = ['luas_tanah', 'luas_bangunan', 'lokasi_bangunan', 'dinding_bangunan',
                       'lantai_bangunan', 'atap_bangunan', 'pagar_bangunan', 'lokasi_jalan',
                       'daya_listrik', 'fungsi_rumah', 'kepemilikan_kendaraan'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('survei', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add foreign key references to master tables
            $table->foreignId('master_luas_tanah_id')->nullable()->constrained('master_luas_tanah')->onDelete('set null');
            $table->foreignId('master_luas_bangunan_id')->nullable()->constrained('master_luas_bangunan')->onDelete('set null');
            $table->foreignId('master_lokasi_bangunan_id')->nullable()->constrained('master_lokasi_bangunan')->onDelete('set null');
            $table->foreignId('master_dinding_bangunan_id')->nullable()->constrained('master_dinding_bangunan')->onDelete('set null');
            $table->foreignId('master_lantai_bangunan_id')->nullable()->constrained('master_lantai_bangunan')->onDelete('set null');
            $table->foreignId('master_atap_bangunan_id')->nullable()->constrained('master_atap_bangunan')->onDelete('set null');
            $table->foreignId('master_pagar_bangunan_id')->nullable()->constrained('master_pagar_bangunan')->onDelete('set null');
            $table->foreignId('master_kondisi_jalan_id')->nullable()->constrained('master_kondisi_jalan')->onDelete('set null');
            $table->foreignId('master_daya_listrik_id')->nullable()->constrained('master_daya_listrik')->onDelete('set null');
            $table->foreignId('master_fungsi_rumah_id')->nullable()->constrained('master_fungsi_rumah')->onDelete('set null');
            $table->foreignId('master_kepemilikan_kendaraan_id')->nullable()->constrained('master_kepemilikan_kendaraan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            // Drop foreign key columns
            $table->dropForeign(['master_luas_tanah_id']);
            $table->dropForeign(['master_luas_bangunan_id']);
            $table->dropForeign(['master_lokasi_bangunan_id']);
            $table->dropForeign(['master_dinding_bangunan_id']);
            $table->dropForeign(['master_lantai_bangunan_id']);
            $table->dropForeign(['master_atap_bangunan_id']);
            $table->dropForeign(['master_pagar_bangunan_id']);
            $table->dropForeign(['master_kondisi_jalan_id']);
            $table->dropForeign(['master_daya_listrik_id']);
            $table->dropForeign(['master_fungsi_rumah_id']);
            $table->dropForeign(['master_kepemilikan_kendaraan_id']);

            $table->dropColumn([
                'master_luas_tanah_id',
                'master_luas_bangunan_id',
                'master_lokasi_bangunan_id',
                'master_dinding_bangunan_id',
                'master_lantai_bangunan_id',
                'master_atap_bangunan_id',
                'master_pagar_bangunan_id',
                'master_kondisi_jalan_id',
                'master_daya_listrik_id',
                'master_fungsi_rumah_id',
                'master_kepemilikan_kendaraan_id',
            ]);

            // Re-add the old enum columns
            $table->enum('luas_tanah', ['0-60', '60-120', '120-200', '200-300', '>300'])->nullable();
            $table->enum('luas_bangunan', ['0-36', '36-70', '70-120', '120-200', '>200'])->nullable();
            $table->enum('lokasi_bangunan', ['gang-sempit', 'gang-sedang', 'tepi-jalan-kecil', 'tepi-jalan-besar', 'jalan-utama'])->nullable();
            $table->enum('dinding_bangunan', ['bambu-kayu', 'semi-permanen', 'tembok-setengah', 'tembok-penuh', 'bata-expose'])->nullable();
            $table->enum('lantai_bangunan', ['tanah', 'semen', 'keramik-biasa', 'keramik-bagus', 'granit-marmer'])->nullable();
            $table->enum('atap_bangunan', ['rumbia-jerami', 'seng-asbes', 'genteng-tanah', 'genteng-beton', 'dak-beton'])->nullable();
            $table->enum('pagar_bangunan', ['tidak-ada', 'bambu-kayu', 'kawat-seng', 'tembok-setengah', 'tembok-penuh'])->nullable();
            $table->enum('lokasi_jalan', ['tanah-berbatu', 'makadam', 'paving-conblock', 'aspal-sedang', 'aspal-mulus'])->nullable();
            $table->enum('daya_listrik', ['non-pln', '450-900', '1300', '2200', '>2200'])->nullable();
            $table->enum('fungsi_rumah', ['kontrak-kost', 'rumah-sendiri', 'rumah-keluarga', 'rumah-dinas', 'rumah-mewah'])->nullable();
            $table->enum('kepemilikan_kendaraan', ['tidak-ada', 'sepeda-becak', 'sepeda-motor', 'mobil-motor', 'mobil-mewah'])->nullable();
        });
    }
};
