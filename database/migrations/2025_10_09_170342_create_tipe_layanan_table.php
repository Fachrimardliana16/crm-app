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
        Schema::create('tipe_layanan', function (Blueprint $table) {
            $table->uuid('id_tipe_layanan')->primary();
            $table->string('kode_tipe_layanan', 10)->nullable(); // Added from missing columns migration
            $table->string('nama_tipe_layanan');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya_standar', 15, 2)->nullable(); // Added from missing columns migration
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_tipe_layanan', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_layanan');
    }
};
