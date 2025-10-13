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
        // Master Luas Tanah
        Schema::create('master_luas_tanah', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('range_min')->nullable();
            $table->string('range_max')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Luas Bangunan
        Schema::create('master_luas_bangunan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('range_min')->nullable();
            $table->string('range_max')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Lokasi Bangunan
        Schema::create('master_lokasi_bangunan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Dinding Bangunan
        Schema::create('master_dinding_bangunan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Lantai Bangunan
        Schema::create('master_lantai_bangunan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Atap Bangunan
        Schema::create('master_atap_bangunan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Pagar Bangunan
        Schema::create('master_pagar_bangunan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Kondisi Jalan
        Schema::create('master_kondisi_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Daya Listrik
        Schema::create('master_daya_listrik', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('range_min')->nullable();
            $table->string('range_max')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Fungsi Rumah
        Schema::create('master_fungsi_rumah', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Master Kepemilikan Kendaraan
        Schema::create('master_kepemilikan_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Add foreign key constraints to survei table after master tables are created
        Schema::table('survei', function (Blueprint $table) {
            $table->foreign('master_luas_tanah_id')->references('id')->on('master_luas_tanah')->onDelete('set null');
            $table->foreign('master_luas_bangunan_id')->references('id')->on('master_luas_bangunan')->onDelete('set null');
            $table->foreign('master_lokasi_bangunan_id')->references('id')->on('master_lokasi_bangunan')->onDelete('set null');
            $table->foreign('master_dinding_bangunan_id')->references('id')->on('master_dinding_bangunan')->onDelete('set null');
            $table->foreign('master_lantai_bangunan_id')->references('id')->on('master_lantai_bangunan')->onDelete('set null');
            $table->foreign('master_atap_bangunan_id')->references('id')->on('master_atap_bangunan')->onDelete('set null');
            $table->foreign('master_pagar_bangunan_id')->references('id')->on('master_pagar_bangunan')->onDelete('set null');
            $table->foreign('master_kondisi_jalan_id')->references('id')->on('master_kondisi_jalan')->onDelete('set null');
            $table->foreign('master_daya_listrik_id')->references('id')->on('master_daya_listrik')->onDelete('set null');
            $table->foreign('master_fungsi_rumah_id')->references('id')->on('master_fungsi_rumah')->onDelete('set null');
            $table->foreign('master_kepemilikan_kendaraan_id')->references('id')->on('master_kepemilikan_kendaraan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys first
        Schema::table('survei', function (Blueprint $table) {
            $table->dropForeign(['master_luas_tanah_id']);
            $table->dropForeign(['master_luas_bangunan_id']);
            $table->dropForeign(['master_lokasi_bangunan_id']);
            $table->dropForeign(['master_dinding_bangunan_id']);
            $table->dropForeign(['master_lantai_bangunan_id']);
            $table->dropForeign(['master_atap_bangunan_id']);
            $table->dropForeign(['master_pagar_bangunan_id']);
            $table->dropForeign(['master_kondisi_jalan_id']);
            $table->dropForeign(['master_daya_listrik_id']);
            $table->dropForeign(['master_fungsi_rumah_id']);
            $table->dropForeign(['master_kepemilikan_kendaraan_id']);
        });

        // Then drop master tables
        Schema::dropIfExists('master_kepemilikan_kendaraan');
        Schema::dropIfExists('master_fungsi_rumah');
        Schema::dropIfExists('master_daya_listrik');
        Schema::dropIfExists('master_kondisi_jalan');
        Schema::dropIfExists('master_pagar_bangunan');
        Schema::dropIfExists('master_atap_bangunan');
        Schema::dropIfExists('master_lantai_bangunan');
        Schema::dropIfExists('master_dinding_bangunan');
        Schema::dropIfExists('master_lokasi_bangunan');
        Schema::dropIfExists('master_luas_bangunan');
        Schema::dropIfExists('master_luas_tanah');
    }
};
