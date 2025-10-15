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
            // Add additional columns needed for survey system (only if not exists)
            if (!Schema::hasColumn('survei', 'lokasi_map')) {
                $table->string('lokasi_map')->nullable()->after('rekomendasi_teknis');
            }

            // Add foreign key references to master tables (only if not exists)
            if (!Schema::hasColumn('survei', 'master_luas_tanah_id')) {
                $table->foreignId('master_luas_tanah_id')->nullable()->constrained('master_luas_tanah')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_luas_bangunan_id')) {
                $table->foreignId('master_luas_bangunan_id')->nullable()->constrained('master_luas_bangunan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_lokasi_bangunan_id')) {
                $table->foreignId('master_lokasi_bangunan_id')->nullable()->constrained('master_lokasi_bangunan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_dinding_bangunan_id')) {
                $table->foreignId('master_dinding_bangunan_id')->nullable()->constrained('master_dinding_bangunan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_lantai_bangunan_id')) {
                $table->foreignId('master_lantai_bangunan_id')->nullable()->constrained('master_lantai_bangunan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_atap_bangunan_id')) {
                $table->foreignId('master_atap_bangunan_id')->nullable()->constrained('master_atap_bangunan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_pagar_bangunan_id')) {
                $table->foreignId('master_pagar_bangunan_id')->nullable()->constrained('master_pagar_bangunan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_kondisi_jalan_id')) {
                $table->foreignId('master_kondisi_jalan_id')->nullable()->constrained('master_kondisi_jalan')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_daya_listrik_id')) {
                $table->foreignId('master_daya_listrik_id')->nullable()->constrained('master_daya_listrik')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_fungsi_rumah_id')) {
                $table->foreignId('master_fungsi_rumah_id')->nullable()->constrained('master_fungsi_rumah')->onDelete('set null');
            }
            if (!Schema::hasColumn('survei', 'master_kepemilikan_kendaraan_id')) {
                $table->foreignId('master_kepemilikan_kendaraan_id')->nullable()->constrained('master_kepemilikan_kendaraan')->onDelete('set null');
            }

            // Add calculated and result columns (only if not exists)
            if (!Schema::hasColumn('survei', 'skor_total')) {
                $table->integer('skor_total')->nullable()->after('master_kepemilikan_kendaraan_id');
            }
            if (!Schema::hasColumn('survei', 'hasil_survei')) {
                $table->enum('hasil_survei', ['direkomendasikan', 'tidak_direkomendasikan', 'perlu_review'])->nullable()->after('skor_total');
            }
            if (!Schema::hasColumn('survei', 'kategori_golongan')) {
                $table->enum('kategori_golongan', ['A', 'B', 'C', 'D'])->nullable()->after('hasil_survei');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            // Drop foreign key constraints first
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

            // Drop all added columns
            $table->dropColumn([
                'lokasi_map',
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
                'skor_total',
                'hasil_survei',
                'kategori_golongan',
            ]);
        });
    }
};
