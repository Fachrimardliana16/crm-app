<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spam', function (Blueprint $table) {
            // Add missing columns that are referenced in the model but not in the original migration
            $table->text('alamat_spam')->nullable()->after('nama_spam');
            $table->string('kelurahan', 100)->nullable()->after('alamat_spam');
            $table->string('kecamatan', 100)->nullable()->after('kelurahan');
            $table->string('kode_pos', 10)->nullable()->after('kecamatan');
            $table->string('telepon', 20)->nullable()->after('kode_pos');
            $table->string('fax', 20)->nullable()->after('telepon');
            $table->string('email', 100)->nullable()->after('fax');
            $table->string('website')->nullable()->after('email');
            $table->decimal('kapasitas_produksi', 10, 2)->nullable()->after('website');
            $table->enum('status_operasional', ['aktif', 'nonaktif', 'maintenance'])->default('aktif')->after('kapasitas_produksi');
            $table->date('tanggal_operasional')->nullable()->after('status_operasional');
            $table->enum('sumber_air', ['Air Tanah', 'Air Permukaan', 'Air Hujan', 'Campuran'])->nullable()->after('tanggal_operasional');
            $table->text('keterangan')->nullable()->after('sumber_air');
        });

        // Drop the existing columns that conflict in separate schema modification
        Schema::table('spam', function (Blueprint $table) {
            $table->dropColumn(['wilayah', 'deskripsi', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spam', function (Blueprint $table) {
            // Restore original columns
            $table->string('wilayah')->after('nama_spam');
            $table->text('deskripsi')->nullable()->after('wilayah');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('deskripsi');
        });
        
        Schema::table('spam', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'alamat_spam',
                'kelurahan',
                'kecamatan',
                'kode_pos',
                'telepon',
                'fax',
                'email',
                'website',
                'kapasitas_produksi',
                'status_operasional',
                'tanggal_operasional',
                'sumber_air',
                'keterangan',
            ]);
        });
    }
};