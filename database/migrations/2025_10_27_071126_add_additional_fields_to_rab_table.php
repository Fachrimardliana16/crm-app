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
        Schema::table('rab', function (Blueprint $table) {
            // Informasi Utama
            $table->enum('jenis_biaya_sambungan', ['standar', 'non_standar'])->nullable()->after('id_pelanggan');
            $table->uuid('id_sub_rayon')->nullable()->after('jenis_biaya_sambungan');
            $table->string('no_langganan', 50)->nullable()->after('id_sub_rayon');
            $table->string('golongan_tarif', 100)->nullable()->after('no_langganan');
            $table->date('tanggal_input')->nullable()->after('golongan_tarif');
            $table->string('nama_pelanggan', 255)->nullable()->after('tanggal_input');
            $table->text('alamat_pelanggan')->nullable()->after('nama_pelanggan');
            $table->string('telepon_pelanggan', 20)->nullable()->after('alamat_pelanggan');
            $table->string('kantor_cabang', 255)->nullable()->after('telepon_pelanggan');
            
            // Rincian Uang Muka
            $table->decimal('perencanaan', 15, 2)->nullable()->after('kantor_cabang');
            $table->decimal('jumlah_uang_muka', 15, 2)->nullable()->after('perencanaan');
            
            // Biaya Instalasi
            $table->decimal('pengerjaan_tanah', 15, 2)->nullable()->after('jumlah_uang_muka');
            $table->decimal('tenaga_kerja', 15, 2)->nullable()->after('pengerjaan_tanah');
            $table->decimal('pipa_accessories', 15, 2)->nullable()->after('tenaga_kerja');
            $table->decimal('jumlah_instalasi', 15, 2)->nullable()->after('pipa_accessories');
            
            // Rincian Piutang
            $table->decimal('pembulatan_piutang', 15, 2)->nullable()->after('jumlah_instalasi');
            $table->decimal('piutang_na', 15, 2)->nullable()->after('pembulatan_piutang');
            $table->decimal('total_piutang', 15, 2)->nullable()->after('piutang_na');
            $table->decimal('pajak_piutang', 15, 2)->nullable()->after('total_piutang');
            $table->decimal('total_biaya_sambungan_baru', 15, 2)->nullable()->after('pajak_piutang');
            
            // Foreign key
            $table->foreign('id_sub_rayon')->references('id_sub_rayon')->on('sub_rayon')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            $table->dropForeign(['id_sub_rayon']);
            $table->dropColumn([
                'jenis_biaya_sambungan',
                'id_sub_rayon',
                'no_langganan',
                'golongan_tarif',
                'tanggal_input',
                'nama_pelanggan',
                'alamat_pelanggan',
                'telepon_pelanggan',
                'kantor_cabang',
                'perencanaan',
                'jumlah_uang_muka',
                'pengerjaan_tanah',
                'tenaga_kerja',
                'pipa_accessories',
                'jumlah_instalasi',
                'pembulatan_piutang',
                'piutang_na',
                'total_piutang',
                'pajak_piutang',
                'total_biaya_sambungan_baru',
            ]);
        });
    }
};
