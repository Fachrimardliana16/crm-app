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
        // Master Data Tables

        // SPAM
        Schema::create('spam', function (Blueprint $table) {
            $table->uuid('id_spam')->primary();
            $table->string('nama_spam');
            $table->string('wilayah');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->index(['status']);
        });

        // AREA
        Schema::create('area', function (Blueprint $table) {
            $table->uuid('id_area')->primary();
            $table->string('kode_area')->unique();
            $table->string('nama_area');
            $table->text('deskripsi')->nullable();
            $table->text('koordinat_batas')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');

            $table->index(['status', 'kode_area']);
        });

        // GOLONGAN_PELANGGAN
        Schema::create('golongan_pelanggan', function (Blueprint $table) {
            $table->uuid('id_golongan')->primary();
            $table->string('kode_golongan')->unique();
            $table->string('nama_golongan');
            $table->text('deskripsi')->nullable();
            $table->decimal('tarif_minimum', 15, 2)->default(0);
            $table->decimal('tarif_per_m3', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->index(['status', 'kode_golongan']);
        });

        // STATUS TABLES - Dynamic creation
        $statusTables = [
            'status_pelanggan',
            'status_pendaftaran',
            'status_rab',
            'status_tagihan',
            'status_pembayaran'
        ];

        foreach ($statusTables as $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->uuid("id_{$tableName}")->primary();
                $table->string('kode_status')->unique();
                $table->string('nama_status');
                $table->text('deskripsi')->nullable();
                $table->string('warna_status')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->string('dibuat_oleh');
                $table->timestamp('dibuat_pada');

                $table->index(['status', 'kode_status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'status_pembayaran',
            'status_tagihan',
            'status_rab',
            'status_pendaftaran',
            'status_pelanggan',
            'golongan_pelanggan',
            'area',
            'spam'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
