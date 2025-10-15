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
            // Menambahkan kolom yang diperlukan untuk form
            $table->integer('skor_total')->nullable()->after('master_kepemilikan_kendaraan_id');
            $table->enum('hasil_survei', ['direkomendasikan', 'tidak_direkomendasikan', 'perlu_review'])->nullable()->after('skor_total');
            $table->enum('kategori_golongan', ['A', 'B', 'C', 'D'])->nullable()->after('hasil_survei');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            $table->dropColumn([
                'skor_total',
                'hasil_survei',
                'kategori_golongan',
            ]);
        });
    }
};
