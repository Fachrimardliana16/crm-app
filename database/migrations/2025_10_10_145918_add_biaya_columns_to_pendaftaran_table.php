<?php

u    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->decimal('biaya_tipe_layanan', 15, 2)->nullable()->after('data_pengembalian');
            $table->decimal('biaya_jenis_daftar', 15, 2)->nullable()->after('biaya_tipe_layanan');
            $table->decimal('biaya_tipe_pendaftaran', 15, 2)->nullable()->after('biaya_jenis_daftar');
            $table->decimal('biaya_tambahan', 15, 2)->nullable()->after('biaya_tipe_pendaftaran');
            $table->decimal('total_biaya_pendaftaran', 15, 2)->nullable()->after('biaya_tambahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'biaya_tipe_layanan',
                'biaya_jenis_daftar',
                'biaya_tipe_pendaftaran',
                'biaya_tambahan',
                'total_biaya_pendaftaran'
            ]);
        });
    }ase\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            //
        });
    }
};
