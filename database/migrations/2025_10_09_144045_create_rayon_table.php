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
        Schema::create('rayon', function (Blueprint $table) {
            $table->uuid('id_rayon')->primary();
            $table->string('kode_rayon', 2)->unique()->comment('Kode Rayon 2 digit: 01, 02, 03, dst');
            $table->string('nama_rayon');
            $table->text('deskripsi')->nullable();
            $table->string('wilayah')->nullable()->comment('Wilayah geografis rayon');
            $table->decimal('koordinat_pusat_lat', 10, 8)->nullable()->comment('Latitude pusat rayon');
            $table->decimal('koordinat_pusat_lng', 11, 8)->nullable()->comment('Longitude pusat rayon');
            $table->integer('radius_coverage')->nullable()->comment('Radius coverage dalam meter');
            $table->integer('jumlah_pelanggan')->default(0)->comment('Jumlah pelanggan aktif');
            $table->integer('kapasitas_maksimal')->nullable()->comment('Kapasitas maksimal pelanggan');
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            // Indexes untuk performance
            $table->index(['status_aktif']);
            $table->index(['kode_rayon', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayon');
    }
};
