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
        Schema::create('sub_rayon', function (Blueprint $table) {
            $table->uuid('id_sub_rayon')->primary();
            $table->uuid('id_rayon')->comment('Foreign key ke tabel rayon');
            $table->string('kode_sub_rayon', 4)->unique()->comment('Kode Sub Rayon 4 digit: 0001, 0002, 0003, dst');
            $table->string('nama_sub_rayon');
            $table->text('deskripsi')->nullable();
            $table->string('wilayah')->nullable()->comment('Wilayah geografis sub rayon');
            $table->decimal('koordinat_pusat_lat', 10, 8)->nullable()->comment('Latitude pusat sub rayon');
            $table->decimal('koordinat_pusat_lng', 11, 8)->nullable()->comment('Longitude pusat sub rayon');
            $table->integer('radius_coverage')->nullable()->comment('Radius coverage dalam meter');
            $table->integer('jumlah_pelanggan')->default(0)->comment('Jumlah pelanggan aktif');
            $table->integer('kapasitas_maksimal')->nullable()->comment('Kapasitas maksimal pelanggan');
            $table->integer('nomor_pelanggan_terakhir')->default(0)->comment('Counter untuk nomor urut pelanggan');
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            // Foreign key constraint
            $table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('cascade');

            // Indexes untuk performance
            $table->index(['id_rayon']);
            $table->index(['status_aktif']);
            $table->index(['kode_sub_rayon', 'status_aktif']);
            $table->index(['id_rayon', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_rayon');
    }
};
