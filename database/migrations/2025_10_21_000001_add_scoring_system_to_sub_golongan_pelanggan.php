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
        Schema::table('sub_golongan_pelanggan', function (Blueprint $table) {
            // Scoring system untuk menentukan sub golongan berdasarkan hasil survei
            $table->integer('skor_minimum')->default(0)->after('urutan')->comment('Skor minimum untuk masuk ke sub golongan ini');
            $table->integer('skor_maksimum')->nullable()->after('skor_minimum')->comment('Skor maksimum untuk sub golongan ini (null = tidak terbatas)');
            $table->text('kriteria_scoring')->nullable()->after('skor_maksimum')->comment('Deskripsi kriteria scoring untuk sub golongan');
            $table->boolean('gunakan_scoring')->default(true)->after('kriteria_scoring')->comment('Apakah menggunakan sistem scoring otomatis');
            $table->integer('prioritas_scoring')->default(0)->after('gunakan_scoring')->comment('Prioritas jika ada overlap skor (semakin tinggi semakin prioritas)');
        });

        // Add indexes for better performance
        Schema::table('sub_golongan_pelanggan', function (Blueprint $table) {
            $table->index(['gunakan_scoring', 'skor_minimum', 'skor_maksimum']);
            $table->index(['id_golongan_pelanggan', 'prioritas_scoring']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_golongan_pelanggan', function (Blueprint $table) {
            $table->dropIndex(['gunakan_scoring', 'skor_minimum', 'skor_maksimum']);
            $table->dropIndex(['id_golongan_pelanggan', 'prioritas_scoring']);
            
            $table->dropColumn([
                'skor_minimum',
                'skor_maksimum',
                'kriteria_scoring',
                'gunakan_scoring',
                'prioritas_scoring',
            ]);
        });
    }
};