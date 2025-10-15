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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Add new columns for enhanced payment functionality
            $table->string('jenis_pembayaran')->default('rekening')->after('metode_bayar');
            $table->decimal('uang_diterima', 15, 2)->nullable()->after('jumlah_bayar');
            $table->decimal('kembalian', 15, 2)->nullable()->after('uang_diterima');
            $table->decimal('total_tagihan', 15, 2)->nullable()->after('kembalian');
            $table->decimal('sisa_tagihan', 15, 2)->default(0)->after('total_tagihan');
            $table->string('periode_pembayaran')->nullable()->after('sisa_tagihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_pembayaran',
                'uang_diterima',
                'kembalian',
                'total_tagihan',
                'sisa_tagihan',
                'periode_pembayaran'
            ]);
        });
    }
};
