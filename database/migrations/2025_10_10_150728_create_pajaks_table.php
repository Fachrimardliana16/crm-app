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
        Schema::create('pajak', function (Blueprint $table) {
            $table->uuid('id_pajak')->primary();
            $table->string('kode_pajak', 10)->unique();
            $table->string('nama_pajak', 255);
            $table->text('deskripsi')->nullable();
            $table->decimal('persentase_pajak', 5, 2)->default(0); // Contoh: 11.00 untuk PPN 11%
            $table->decimal('nilai_tetap', 15, 2)->nullable(); // Untuk pajak dengan nilai tetap
            $table->enum('jenis_pajak', ['persentase', 'nilai_tetap'])->default('persentase');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajak');
    }
};
