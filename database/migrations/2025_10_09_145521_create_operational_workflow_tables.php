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
        // PENDAFTARAN - Customer registration workflow
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->uuid('id_pendaftaran')->primary();
            $table->string('nomor_registrasi')->unique(); // Added from update migration
            $table->uuid('id_cabang'); // Added from update migration
            $table->uuid('id_pelanggan')->nullable(); // Made nullable from update migration
            $table->string('status_pendaftaran')->default('draft');
            $table->string('cabang_pendaftaran')->nullable();
            $table->string('kelurahan_pemasangan')->nullable(); // Made nullable from make migration
            $table->uuid('id_kelurahan')->nullable(); // Added from update migration
            $table->string('tipe_layanan')->nullable();
            $table->uuid('id_tipe_layanan')->nullable(); // Added from update migration
            $table->string('pekerjaan_pemohon')->nullable();
            $table->uuid('id_pekerjaan')->nullable(); // Added from update migration
            $table->string('jenis_identitas')->nullable();
            $table->string('nomor_identitas')->nullable(); // Encrypted
            $table->date('tanggal_daftar');
            $table->string('nama_pemohon');
            $table->string('no_hp_pemohon')->nullable(); // Added missing column
            $table->text('alamat_pemasangan');

            // Location Data
            $table->decimal('latitude_awal', 10, 8)->nullable();
            $table->decimal('longitude_awal', 11, 8)->nullable();
            $table->decimal('elevasi_awal_mdpl', 8, 2)->nullable();
            $table->text('keterangan_arah_lokasi')->nullable();

            // Documents (Encrypted file paths)
            $table->string('scan_identitas_utama')->nullable();
            $table->string('scan_dokumen_mou')->nullable();

            // Financial
            $table->decimal('dana_pengembalian', 15, 2)->default(0);
            $table->text('data_pengembalian')->nullable(); // Added from missing columns migration
            $table->decimal('biaya_tipe_layanan', 15, 2)->nullable(); // Added from biaya migration
            $table->decimal('biaya_jenis_daftar', 15, 2)->nullable(); // Added from biaya migration
            $table->decimal('biaya_tipe_pendaftaran', 15, 2)->nullable(); // Added from biaya migration
            $table->decimal('biaya_tambahan', 15, 2)->nullable(); // Added from biaya migration
            $table->decimal('subtotal_biaya', 15, 2)->nullable(); // Added missing column
            $table->decimal('total_biaya_pendaftaran', 15, 2)->nullable(); // Added from biaya migration

            // Pajak columns (Added from pajak migration)
            $table->uuid('id_pajak')->nullable();
            $table->decimal('nilai_pajak', 15, 2)->nullable();
            $table->decimal('pajak_ppn', 15, 2)->nullable();
            $table->decimal('total_pajak', 15, 2)->nullable();
            $table->decimal('total_biaya_termasuk_pajak', 15, 2)->nullable();

            // Installation Details
            $table->enum('ada_toren', ['ya', 'tidak'])->default('tidak');
            $table->enum('ada_sumur', ['ya', 'tidak'])->default('tidak');
            $table->enum('jenis_daftar', ['standar', 'non_standar'])->default('standar');
            $table->uuid('id_jenis_daftar')->nullable(); // Added from update migration
            $table->enum('tipe_daftar', ['standar', 'kilat'])->default('standar');
            $table->uuid('id_tipe_pendaftaran')->nullable(); // Added from update migration

            // Additional fields from missing columns migration
            $table->string('jenis_air_baku')->nullable();
            $table->string('sistem_distribusi')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas', 'sebagian'])->default('belum_bayar');
            $table->timestamp('tanggal_pembayaran')->nullable();

            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_pendaftaran', 'tanggal_daftar']);
            $table->index(['tipe_layanan', 'jenis_daftar']);
        });

        // SURVEI - Technical survey process
        Schema::create('survei', function (Blueprint $table) {
            $table->uuid('id_survei')->primary();
            $table->uuid('id_pendaftaran');
            $table->uuid('id_pelanggan');
            $table->uuid('id_spam')->nullable();
            $table->string('nip_surveyor');
            $table->date('tanggal_survei');
            $table->enum('status_survei', ['draft', 'disetujui', 'ditolak'])->default('draft');
            $table->string('subrayon')->nullable();

            // Verified Location Data
            $table->decimal('latitude_terverifikasi', 10, 8)->nullable();
            $table->decimal('longitude_terverifikasi', 11, 8)->nullable();
            $table->decimal('elevasi_terverifikasi_mdpl', 8, 2)->nullable();
            $table->decimal('jarak_pemasangan', 8, 2)->nullable();

            $table->text('catatan_teknis')->nullable();
            $table->integer('nilai_survei')->nullable();
            $table->string('golongan_survei')->nullable();
            $table->string('kelas_survei_input')->nullable();

            // Photo Documentation (Encrypted file paths)
            $table->string('foto_peta_lokasi')->nullable();
            $table->string('foto_tanah_bangunan')->nullable();
            $table->string('foto_dinding')->nullable();
            $table->string('foto_lantai')->nullable();
            $table->string('foto_atap')->nullable();
            $table->string('foto_pagar')->nullable();
            $table->string('foto_jalan')->nullable();
            $table->string('foto_meteran_listrik')->nullable();

            $table->text('rekomendasi_teknis')->nullable();

            // Map data
            $table->string('lokasi_map')->nullable();

            // Master data foreign keys (will be constrained later)
            $table->unsignedBigInteger('master_luas_tanah_id')->nullable();
            $table->unsignedBigInteger('master_luas_bangunan_id')->nullable();
            $table->unsignedBigInteger('master_lokasi_bangunan_id')->nullable();
            $table->unsignedBigInteger('master_dinding_bangunan_id')->nullable();
            $table->unsignedBigInteger('master_lantai_bangunan_id')->nullable();
            $table->unsignedBigInteger('master_atap_bangunan_id')->nullable();
            $table->unsignedBigInteger('master_pagar_bangunan_id')->nullable();
            $table->unsignedBigInteger('master_kondisi_jalan_id')->nullable();
            $table->unsignedBigInteger('master_daya_listrik_id')->nullable();
            $table->unsignedBigInteger('master_fungsi_rumah_id')->nullable();
            $table->unsignedBigInteger('master_kepemilikan_kendaraan_id')->nullable();

            // Calculated and result columns
            $table->integer('skor_total')->nullable();
            $table->enum('hasil_survei', ['direkomendasikan', 'tidak_direkomendasikan', 'perlu_review'])->nullable();
            $table->enum('kategori_golongan', ['A', 'B', 'C', 'D'])->nullable();

            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_spam')->references('id_spam')->on('spam')->onDelete('set null');
            $table->index(['status_survei', 'tanggal_survei']);
            $table->index(['nip_surveyor', 'id_spam']);
        });

        // RAB - Budget Estimate
        Schema::create('rab', function (Blueprint $table) {
            $table->uuid('id_rab')->primary();
            $table->uuid('id_pendaftaran');
            $table->uuid('id_pelanggan');
            $table->date('tanggal_rab_dibuat');
            $table->string('status_rab')->default('draft');

            // Cost Breakdown
            $table->decimal('total_biaya_konstruksi', 15, 2)->default(0);
            $table->decimal('total_biaya_administrasi', 15, 2)->default(0);
            $table->decimal('sub_total_awal', 15, 2)->default(0);
            $table->decimal('nilai_pajak', 15, 2)->default(0);
            $table->decimal('total_rab_bruto', 15, 2)->default(0);
            $table->decimal('pembulatan', 15, 2)->default(0);
            $table->decimal('total_final_rab', 15, 2)->default(0);

            // Payment Terms
            $table->decimal('uang_muka', 15, 2)->default(0);
            $table->decimal('biaya_sb', 15, 2)->default(0);
            $table->decimal('piutang_non_adir', 15, 2)->default(0);
            $table->integer('jumlah_angsuran')->default(1);
            $table->enum('status_pembayaran', ['lunas', 'sebagian', 'belum'])->default('belum');

            $table->text('catatan_rab')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_rab', 'status_pembayaran']);
            $table->index(['tanggal_rab_dibuat']);
        });

        // TAGIHAN_RAB - RAB Invoices
        Schema::create('tagihan_rab', function (Blueprint $table) {
            $table->uuid('id_tagihan')->primary();
            $table->uuid('id_rab');
            $table->uuid('id_pelanggan');
            $table->string('nomor_tagihan')->unique();
            $table->date('tanggal_terbit');
            $table->date('jatuh_tempo');
            $table->decimal('total_tertagih', 15, 2);
            $table->enum('status_pembayaran', ['lunas', 'sebagian', 'belum'])->default('belum');
            $table->text('catatan_tagihan')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');

            $table->foreign('id_rab')->references('id_rab')->on('rab')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_pembayaran', 'jatuh_tempo']);
            $table->index(['nomor_tagihan']);
        });

        // PEMBAYARAN - Payment records
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id_pembayaran')->primary();
            $table->uuid('id_tagihan')->nullable();
            $table->uuid('id_pelanggan');
            $table->string('nomor_pembayaran')->unique();
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->string('bukti_bayar')->nullable(); // Encrypted file path
            $table->string('metode_bayar');
            $table->string('nip_petugas_loket')->nullable();
            $table->enum('status_verifikasi', ['valid', 'tidak_valid', 'pending'])->default('pending');
            $table->text('catatan_pembayaran')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');

            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihan_rab')->onDelete('set null');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_verifikasi', 'tanggal_bayar']);
            $table->index(['metode_bayar', 'nip_petugas_loket']);
        });

        // RINCIAN_ANGSURAN - Installment details
        Schema::create('rincian_angsuran', function (Blueprint $table) {
            $table->uuid('id_angsuran')->primary();
            $table->uuid('id_tagihan');
            $table->integer('nomor_angsuran');
            $table->decimal('jumlah_angsuran', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status_bayar', ['belum', 'lunas', 'terlambat'])->default('belum');
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('denda', 15, 2)->default(0);
            $table->text('keterangan')->nullable();

            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihan_rab')->onDelete('cascade');
            $table->index(['status_bayar', 'tanggal_jatuh_tempo']);
        });

        // INSTALASI - Installation process
        Schema::create('instalasi', function (Blueprint $table) {
            $table->uuid('id_instalasi')->primary();
            $table->uuid('id_pendaftaran');
            $table->uuid('id_pelanggan');
            $table->string('nip_teknisi');
            $table->date('tanggal_instalasi');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->enum('status_instalasi', ['terjadwal', 'progres', 'selesai', 'ditunda'])->default('terjadwal');

            // Technical Details
            $table->string('nomor_meter')->nullable();
            $table->string('merk_meter')->nullable();
            $table->decimal('posisi_meter_latitude', 10, 8)->nullable();
            $table->decimal('posisi_meter_longitude', 11, 8)->nullable();
            $table->decimal('elevasi_meter_mdpl', 8, 2)->nullable();
            $table->decimal('tekanan_air', 8, 2)->nullable();
            $table->string('jenis_pipa')->nullable();
            $table->decimal('diameter_pipa', 8, 2)->nullable();

            // Photo Documentation (Encrypted file paths)
            $table->string('foto_meter_terpasang')->nullable();
            $table->string('foto_instalasi_pipa')->nullable();
            $table->string('foto_hasil_instalasi')->nullable();

            $table->text('catatan_instalasi')->nullable();
            $table->text('kendala_teknis')->nullable();
            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_instalasi', 'tanggal_instalasi']);
            $table->index(['nip_teknisi', 'id_pelanggan']);
        });

        // PENGADUAN - Complaint management
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->uuid('id_pengaduan')->primary();
            $table->uuid('id_pelanggan');
            $table->string('nomor_pengaduan')->unique();
            $table->date('tanggal_pengaduan');
            $table->time('jam_pengaduan');
            $table->string('kategori_pengaduan');
            $table->string('jenis_pengaduan');
            $table->text('uraian_pengaduan');
            $table->enum('prioritas', ['rendah', 'normal', 'tinggi', 'darurat'])->default('normal');
            $table->enum('status_pengaduan', ['diterima', 'diproses', 'selesai', 'ditutup'])->default('diterima');

            // Resolution Details
            $table->date('tanggal_target_selesai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('solusi_diberikan')->nullable();
            $table->string('nip_petugas_penanganan')->nullable();
            $table->decimal('biaya_penanganan', 15, 2)->default(0);

            // Photo Documentation (Encrypted file paths)
            $table->string('foto_kondisi_awal')->nullable();
            $table->string('foto_kondisi_akhir')->nullable();

            $table->enum('tingkat_kepuasan', ['sangat_puas', 'puas', 'cukup', 'kurang_puas', 'tidak_puas'])->nullable();
            $table->text('feedback_pelanggan')->nullable();

            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');
            $table->string('diperbarui_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_pengaduan', 'prioritas']);
            $table->index(['kategori_pengaduan', 'tanggal_pengaduan']);
            $table->index(['nomor_pengaduan']);
        });

        // BACAAN_METER - Meter reading records
        Schema::create('bacaan_meter', function (Blueprint $table) {
            $table->uuid('id_bacaan')->primary();
            $table->uuid('id_pelanggan');
            $table->string('periode_bacaan');
            $table->date('tanggal_bacaan');
            $table->string('nip_petugas_baca');
            $table->integer('angka_meter_sebelum')->default(0);
            $table->integer('angka_meter_sekarang');
            $table->integer('pemakaian_air');
            $table->enum('status_bacaan', ['normal', 'taksir', 'meter_rusak', 'tidak_terbaca'])->default('normal');
            $table->text('catatan_bacaan')->nullable();

            // Photo Documentation (Encrypted file path)
            $table->string('foto_meter')->nullable();

            $table->decimal('koordinat_bacaan_lat', 10, 8)->nullable();
            $table->decimal('koordinat_bacaan_lng', 11, 8)->nullable();
            $table->timestamp('waktu_bacaan');

            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['periode_bacaan', 'status_bacaan']);
            $table->index(['id_pelanggan', 'tanggal_bacaan']);
            $table->unique(['id_pelanggan', 'periode_bacaan']);
        });

        // TAGIHAN_BULANAN - Monthly bills
        Schema::create('tagihan_bulanan', function (Blueprint $table) {
            $table->uuid('id_tagihan_bulanan')->primary();
            $table->uuid('id_pelanggan');
            $table->uuid('id_bacaan');
            $table->string('periode_tagihan');
            $table->date('tanggal_terbit');
            $table->date('jatuh_tempo');

            // Calculation Details
            $table->integer('pemakaian_air');
            $table->decimal('tarif_dasar', 15, 2);
            $table->decimal('biaya_pemakaian', 15, 2);
            $table->decimal('biaya_beban', 15, 2)->default(0);
            $table->decimal('biaya_administrasi', 15, 2)->default(0);
            $table->decimal('biaya_pemeliharaan', 15, 2)->default(0);
            $table->decimal('biaya_meter', 15, 2)->default(0);
            $table->decimal('biaya_denda', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2);

            $table->enum('status_pembayaran', ['belum', 'lunas', 'sebagian'])->default('belum');
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('jumlah_bayar', 15, 2)->default(0);

            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_bacaan')->references('id_bacaan')->on('bacaan_meter')->onDelete('cascade');
            $table->index(['periode_tagihan', 'status_pembayaran']);
            $table->index(['jatuh_tempo', 'id_pelanggan']);
            $table->unique(['id_pelanggan', 'periode_tagihan']);
        });

        // PEMBAYARAN_BULANAN - Monthly payment records
        Schema::create('pembayaran_bulanan', function (Blueprint $table) {
            $table->uuid('id_pembayaran_bulanan')->primary();
            $table->uuid('id_tagihan_bulanan');
            $table->uuid('id_pelanggan');
            $table->string('nomor_pembayaran_bulanan')->unique();
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->string('metode_bayar');
            $table->string('nomor_referensi')->nullable();
            $table->string('bukti_bayar')->nullable(); // Encrypted file path
            $table->string('nip_petugas_loket')->nullable();
            $table->enum('status_verifikasi', ['valid', 'tidak_valid', 'pending'])->default('pending');
            $table->text('catatan_pembayaran')->nullable();

            $table->string('dibuat_oleh');
            $table->timestamp('dibuat_pada');

            $table->foreign('id_tagihan_bulanan')->references('id_tagihan_bulanan')->on('tagihan_bulanan')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->index(['status_verifikasi', 'tanggal_bayar']);
            $table->index(['metode_bayar', 'nomor_referensi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_bulanan');
        Schema::dropIfExists('tagihan_bulanan');
        Schema::dropIfExists('bacaan_meter');
        Schema::dropIfExists('pengaduan');
        Schema::dropIfExists('instalasi');
        Schema::dropIfExists('rincian_angsuran');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('tagihan_rab');
        Schema::dropIfExists('rab');
        Schema::dropIfExists('survei');
        Schema::dropIfExists('pendaftaran');
    }
};
