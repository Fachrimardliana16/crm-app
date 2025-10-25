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
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['id_pelanggan']);
            
            // Ubah kolom menjadi nullable
            $table->uuid('id_pelanggan')->nullable()->change();
            
            // Tambahkan kembali foreign key constraint
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['id_pelanggan']);
            
            // Ubah kolom kembali ke not nullable
            $table->uuid('id_pelanggan')->nullable(false)->change();
            
            // Tambahkan kembali foreign key constraint dengan cascade
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
        });
    }
};
