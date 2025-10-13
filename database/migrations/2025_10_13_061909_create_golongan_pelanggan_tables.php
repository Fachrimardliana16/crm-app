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
        // Create golongan_pelanggan table (parent categories)
        Schema::create('golongan_pelanggan', function (Blueprint $table) {
            $table->uuid('id_golongan_pelanggan')->primary();
            $table->string('kode_golongan', 10)->unique(); // SOC, KOM, IND, etc.
            $table->string('nama_golongan'); // Sosial, Komersial, Industri, etc.
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // untuk sorting
            $table->timestamps();
        });

        // Create sub_golongan_pelanggan table (sub categories)
        Schema::create('sub_golongan_pelanggan', function (Blueprint $table) {
            $table->uuid('id_sub_golongan_pelanggan')->primary();
            $table->uuid('id_golongan_pelanggan'); // FK to golongan_pelanggan
            $table->string('kode_sub_golongan', 20)->unique(); // SOC-UM, SOC-KH, etc.
            $table->string('nama_sub_golongan'); // Sosial Umum, Sosial Khusus, etc.
            $table->text('deskripsi')->nullable();
            
            // Tarif struktur
            $table->decimal('tarif_dasar', 15, 2)->nullable(); // base tariff
            $table->decimal('tarif_per_m3', 15, 2)->nullable(); // rate per cubic meter
            $table->integer('batas_minimum_m3')->default(0); // minimum usage
            
            // Tarif progresif
            $table->decimal('tarif_progresif_1', 15, 2)->nullable(); // blok kedua
            $table->decimal('tarif_progresif_2', 15, 2)->nullable(); // blok ketiga
            $table->decimal('tarif_progresif_3', 15, 2)->nullable(); // blok keempat
            
            // Biaya tetap
            $table->decimal('biaya_beban_tetap', 15, 2)->default(0); // monthly fixed cost
            $table->decimal('biaya_administrasi', 15, 2)->default(0); // admin fee
            $table->decimal('biaya_pemeliharaan', 15, 2)->default(0); // maintenance fee
            
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // untuk sorting
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_golongan_pelanggan')
                  ->references('id_golongan_pelanggan')
                  ->on('golongan_pelanggan')
                  ->onDelete('cascade');
        });

        // Add indexes for performance
        Schema::table('golongan_pelanggan', function (Blueprint $table) {
            $table->index(['is_active', 'urutan']);
            $table->index('kode_golongan');
        });

        Schema::table('sub_golongan_pelanggan', function (Blueprint $table) {
            $table->index(['id_golongan_pelanggan', 'is_active', 'urutan']);
            $table->index('kode_sub_golongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_golongan_pelanggan');
        Schema::dropIfExists('golongan_pelanggan');
    }
};
