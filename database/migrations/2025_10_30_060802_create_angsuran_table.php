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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_rab');
            $table->foreign('id_rab')->references('id_rab')->on('rab')->onDelete('cascade');
            $table->integer('periode_tagihan'); // Format YYYYMM (contoh: 202410)
            $table->integer('angsuran_ke'); // Angsuran ke berapa (1, 2, 3, dst)
            $table->decimal('nominal_angsuran', 15, 2); // Nominal per angsuran
            $table->decimal('sisa_pokok', 15, 2)->nullable(); // Sisa pokok hutang
            $table->enum('status_bayar', ['belum_bayar', 'sudah_bayar', 'terlambat'])->default('belum_bayar');
            $table->date('tanggal_jatuh_tempo'); // Tanggal jatuh tempo bayar
            $table->date('tanggal_bayar')->nullable(); // Tanggal actual pembayaran
            $table->decimal('denda', 15, 2)->default(0); // Denda keterlambatan
            $table->decimal('total_bayar', 15, 2)->nullable(); // Total yang dibayar (termasuk denda)
            $table->text('catatan')->nullable();
            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
            $table->timestamps();
            
            // Indexes untuk performance
            $table->index(['id_rab', 'periode_tagihan']);
            $table->index('periode_tagihan');
            $table->index('status_bayar');
            $table->index('tanggal_jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
