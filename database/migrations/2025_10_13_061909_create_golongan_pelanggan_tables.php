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

            // Struktur tarif PDAM Purbalingga (sistem blok per 10 m³)
            $table->decimal('biaya_tetap_subgolongan', 15, 2)->default(0)->comment('Biaya tetap bulanan berdasarkan sub golongan');
            $table->decimal('tarif_blok_1', 15, 2)->default(0)->comment('Tarif untuk pemakaian 0-10 m³');
            $table->decimal('tarif_blok_2', 15, 2)->default(0)->comment('Tarif untuk pemakaian 11-20 m³');
            $table->decimal('tarif_blok_3', 15, 2)->default(0)->comment('Tarif untuk pemakaian 21-30 m³');
            $table->decimal('tarif_blok_4', 15, 2)->default(0)->comment('Tarif untuk pemakaian >30 m³');

            // Tarif struktur tambahan (untuk fleksibilitas sistem)
            $table->decimal('tarif_dasar', 15, 2)->nullable()->comment('Base tariff (alternatif)');
            $table->decimal('tarif_per_m3', 15, 2)->nullable()->comment('Rate per cubic meter (alternatif)');
            $table->integer('batas_minimum_m3')->default(0)->comment('Minimum usage threshold');

            // Biaya tambahan
            $table->decimal('biaya_beban_tetap', 15, 2)->default(0)->comment('Monthly fixed cost');
            $table->decimal('biaya_administrasi', 15, 2)->default(0)->comment('Administration fee');
            $table->decimal('biaya_pemeliharaan', 15, 2)->default(0)->comment('Maintenance fee');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // untuk sorting
            $table->timestamps();
        });

        // Add indexes for performance
        Schema::table('golongan_pelanggan', function (Blueprint $table) {
            $table->index(['is_active', 'urutan']);
            $table->index('kode_golongan');
        });

        Schema::table('sub_golongan_pelanggan', function (Blueprint $table) {
            $table->foreign('id_golongan_pelanggan')->references('id_golongan_pelanggan')->on('golongan_pelanggan')->onDelete('cascade');
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
