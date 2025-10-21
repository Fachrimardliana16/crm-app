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
            // Kolom untuk menyimpan rekomendasi sub golongan
            $table->uuid('rekomendasi_sub_golongan_id')->nullable()->after('kategori_golongan');
            $table->text('rekomendasi_sub_golongan_text')->nullable()->after('rekomendasi_sub_golongan_id');
            
            // Foreign key ke sub_golongan_pelanggan
            $table->foreign('rekomendasi_sub_golongan_id')
                  ->references('id_sub_golongan_pelanggan')
                  ->on('sub_golongan_pelanggan')
                  ->onDelete('set null');
                  
            // Index untuk performa
            $table->index('rekomendasi_sub_golongan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            $table->dropForeign(['rekomendasi_sub_golongan_id']);
            $table->dropIndex(['rekomendasi_sub_golongan_id']);
            $table->dropColumn([
                'rekomendasi_sub_golongan_id',
                'rekomendasi_sub_golongan_text',
            ]);
        });
    }
};