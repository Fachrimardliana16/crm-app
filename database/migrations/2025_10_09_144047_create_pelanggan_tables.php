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
        // PELANGGAN - Main customer table
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->uuid('id_pelanggan')->primary();
            $table->string('nomor_pelanggan')->unique();
            $table->string('nama_pelanggan');
            $table->string('nik')->nullable(); // Encrypted
            $table->string('jenis_identitas')->nullable();
            $table->string('nomor_identitas')->nullable(); // Encrypted
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat');
            $table->string('rt_rw')->nullable();
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos')->nullable();
            $table->string('nomor_hp')->nullable(); // Encrypted
            $table->string('nomor_telepon')->nullable(); // Encrypted
            $table->string('email')->nullable(); // Encrypted
            $table->string('status_pelanggan');
            $table->string('golongan');
            $table->string('tipe_pelanggan')->nullable();
            $table->string('segment')->nullable();
            $table->uuid('id_area')->nullable();
            $table->uuid('id_spam')->nullable();
            
            // Rayon & Sub Rayon untuk sistem penomoran pelanggan
            $table->uuid('id_rayon')->nullable()->comment('Foreign key ke tabel rayon');
            $table->uuid('id_sub_rayon')->nullable()->comment('Foreign key ke tabel sub_rayon');

            // GIS Data
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('elevasi', 8, 2)->nullable();
            $table->string('kode_gis')->nullable();
            $table->enum('status_gis', ['belum_divalidasi', 'valid', 'tidak_valid', 'revisi'])->default('belum_divalidasi');
            $table->date('tgl_validasi_gis')->nullable();
            $table->string('validasi_gis_oleh')->nullable();
            $table->text('keterangan_gis')->nullable();

            // General Info
            $table->text('keterangan')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            // Historical Data
            $table->enum('status_historis', ['aktif', 'nonaktif', 'arsip'])->default('aktif');
            $table->date('tanggal_nonaktif')->nullable();
            $table->date('tanggal_arsip')->nullable();

            // Foreign Keys
            $table->foreign('id_area')->references('id_area')->on('area')->onDelete('set null');
            $table->foreign('id_spam')->references('id_spam')->on('spam')->onDelete('set null');
            $table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('set null');
            $table->foreign('id_sub_rayon')->references('id_sub_rayon')->on('sub_rayon')->onDelete('set null');

            // Indexes
            $table->index(['nomor_pelanggan', 'status_pelanggan']);
            $table->index(['status_historis', 'status_gis']);
            $table->index(['id_area', 'id_spam']);
            $table->index(['id_rayon', 'id_sub_rayon']);
            $table->index(['kecamatan', 'kelurahan']);
        });

        // GIS_LOG - GIS tracking history
        Schema::create('gis_log', function (Blueprint $table) {
            $table->uuid('id_gis_log')->primary();
            $table->uuid('id_pelanggan');
            $table->enum('aksi_gis', ['insert', 'update', 'delete', 'validasi', 'revisi']);
            $table->timestamp('waktu_aksi');
            $table->string('user_aksi');

            // Old Values
            $table->decimal('latitude_lama', 10, 8)->nullable();
            $table->decimal('longitude_lama', 11, 8)->nullable();
            $table->decimal('elevasi_lama', 8, 2)->nullable();
            $table->string('kode_gis_lama')->nullable();
            $table->string('status_gis_lama')->nullable();

            // New Values
            $table->decimal('latitude_baru', 10, 8)->nullable();
            $table->decimal('longitude_baru', 11, 8)->nullable();
            $table->decimal('elevasi_baru', 8, 2)->nullable();
            $table->string('kode_gis_baru')->nullable();
            $table->string('status_gis_baru')->nullable();

            $table->text('keterangan')->nullable();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['id_pelanggan', 'waktu_aksi']);
            $table->index(['aksi_gis', 'user_aksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gis_log');
        Schema::dropIfExists('pelanggan');
    }
};
