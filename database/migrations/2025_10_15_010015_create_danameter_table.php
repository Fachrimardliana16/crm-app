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
        Schema::create('danameter', function (Blueprint $table) {
            $table->uuid('id_danameter')->primary();
            $table->string('kode_danameter', 10)->unique(); // 1/2", 3/4", 1", dll
            $table->string('diameter_pipa', 20); // Diameter dalam format string untuk fleksibilitas
            $table->decimal('tarif_danameter', 15, 2); // Tarif danameter
            $table->text('deskripsi')->nullable(); // Deskripsi tambahan
            $table->boolean('is_active')->default(true); // Status aktif
            $table->integer('urutan')->default(0); // Urutan tampilan
            $table->timestamps();

            // Index untuk performa
            $table->index(['is_active', 'urutan']);
            $table->index('kode_danameter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danameter');
    }
};
