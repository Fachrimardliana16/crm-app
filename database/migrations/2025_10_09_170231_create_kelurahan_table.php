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
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->uuid('id_kelurahan')->primary();
            $table->string('kode_kelurahan', 15)->unique();
            $table->string('nama_kelurahan');
            $table->uuid('id_kecamatan');
            $table->string('kode_pos', 10)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('kecamatan')->onDelete('cascade');
            $table->index(['kode_kelurahan', 'id_kecamatan', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
    }
};
