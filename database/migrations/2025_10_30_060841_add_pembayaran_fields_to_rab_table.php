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
        Schema::table('rab', function (Blueprint $table) {
            $table->enum('tipe_pembayaran', ['lunas', 'cicilan'])->default('lunas')->after('total_biaya_sambungan_baru');
            $table->integer('jumlah_cicilan')->nullable()->after('tipe_pembayaran'); // Berapa kali cicilan (3, 6, 12, dst)
            $table->decimal('nominal_per_cicilan', 15, 2)->nullable()->after('jumlah_cicilan'); // Nominal per cicilan
            $table->integer('periode_mulai_cicilan')->nullable()->after('nominal_per_cicilan'); // Format YYYYMM kapan cicilan dimulai
            $table->text('catatan_pembayaran')->nullable()->after('periode_mulai_cicilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            $table->dropColumn([
                'tipe_pembayaran',
                'jumlah_cicilan', 
                'nominal_per_cicilan',
                'periode_mulai_cicilan',
                'catatan_pembayaran'
            ]);
        });
    }
};
