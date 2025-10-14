<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GolonganPelanggan;
use App\Models\SubGolonganPelanggan;
use Illuminate\Support\Str;

class GolonganPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SOSIAL
        $sosial = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'SOC',
            'nama_golongan' => 'Sosial',
            'deskripsi' => 'Golongan pelanggan untuk keperluan sosial',
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $sosial->id_golongan_pelanggan,
            'kode_sub_golongan' => 'SOC-KH',
            'nama_sub_golongan' => 'Sosial Khusus',
            'deskripsi' => 'Rumah ibadah, panti asuhan, yayasan sosial',
            'tarif_dasar' => 8000,
            'tarif_per_m3' => 1200,
            'batas_minimum_m3' => 5,
            'tarif_progresif_1' => 1500,
            'tarif_progresif_2' => 1800,
            'tarif_progresif_3' => 2000,
            'biaya_beban_tetap' => 3000,
            'biaya_administrasi' => 1000,
            'biaya_pemeliharaan' => 500,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $sosial->id_golongan_pelanggan,
            'kode_sub_golongan' => 'SOC-HU',
            'nama_sub_golongan' => 'Sosial Umum (HU)',
            'deskripsi' => 'Sekolah, puskesmas, fasilitas umum lainnya',
            'tarif_dasar' => 12000,
            'tarif_per_m3' => 1800,
            'batas_minimum_m3' => 8,
            'tarif_progresif_1' => 2200,
            'tarif_progresif_2' => 2500,
            'tarif_progresif_3' => 2800,
            'biaya_beban_tetap' => 5000,
            'biaya_administrasi' => 1500,
            'biaya_pemeliharaan' => 800,
            'is_active' => true,
            'urutan' => 2,
        ]);

        // 2. RUMAH TANGGA
        $rumahTangga = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'RT',
            'nama_golongan' => 'Rumah Tangga',
            'deskripsi' => 'Golongan pelanggan untuk keperluan rumah tangga',
            'is_active' => true,
            'urutan' => 2,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-A',
            'nama_sub_golongan' => 'Rumah Tangga A',
            'deskripsi' => 'Rumah tangga kategori A (0-10m³)',
            'tarif_dasar' => 15000,
            'tarif_per_m3' => 2000,
            'batas_minimum_m3' => 10,
            'tarif_progresif_1' => 2500,
            'tarif_progresif_2' => 3000,
            'tarif_progresif_3' => 3500,
            'biaya_beban_tetap' => 8000,
            'biaya_administrasi' => 2500,
            'biaya_pemeliharaan' => 1500,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-B',
            'nama_sub_golongan' => 'Rumah Tangga B',
            'deskripsi' => 'Rumah tangga kategori B (11-20m³)',
            'tarif_dasar' => 18000,
            'tarif_per_m3' => 2500,
            'batas_minimum_m3' => 12,
            'tarif_progresif_1' => 3000,
            'tarif_progresif_2' => 3500,
            'tarif_progresif_3' => 4000,
            'biaya_beban_tetap' => 10000,
            'biaya_administrasi' => 3000,
            'biaya_pemeliharaan' => 2000,
            'is_active' => true,
            'urutan' => 2,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-C',
            'nama_sub_golongan' => 'Rumah Tangga C',
            'deskripsi' => 'Rumah tangga kategori C (>20m³)',
            'tarif_dasar' => 22000,
            'tarif_per_m3' => 3000,
            'batas_minimum_m3' => 15,
            'tarif_progresif_1' => 3500,
            'tarif_progresif_2' => 4000,
            'tarif_progresif_3' => 4500,
            'biaya_beban_tetap' => 12000,
            'biaya_administrasi' => 3500,
            'biaya_pemeliharaan' => 2500,
            'is_active' => true,
            'urutan' => 3,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-KH',
            'nama_sub_golongan' => 'Rumah Tangga Khusus',
            'deskripsi' => 'Rumah tangga dengan kebutuhan khusus',
            'tarif_dasar' => 25000,
            'tarif_per_m3' => 3500,
            'batas_minimum_m3' => 20,
            'tarif_progresif_1' => 4000,
            'tarif_progresif_2' => 4500,
            'tarif_progresif_3' => 5000,
            'biaya_beban_tetap' => 15000,
            'biaya_administrasi' => 4000,
            'biaya_pemeliharaan' => 3000,
            'is_active' => true,
            'urutan' => 4,
        ]);

        // 3. INSTANSI
        $instansi = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'INS',
            'nama_golongan' => 'Instansi',
            'deskripsi' => 'Golongan pelanggan untuk instansi pemerintahan',
            'is_active' => true,
            'urutan' => 3,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $instansi->id_golongan_pelanggan,
            'kode_sub_golongan' => 'INS-PEM',
            'nama_sub_golongan' => 'Instansi Pemerintahan',
            'deskripsi' => 'Kantor pemerintahan, dinas, BUMN/BUMD',
            'tarif_dasar' => 30000,
            'tarif_per_m3' => 4000,
            'batas_minimum_m3' => 15,
            'tarif_progresif_1' => 4500,
            'tarif_progresif_2' => 5000,
            'tarif_progresif_3' => 5500,
            'biaya_beban_tetap' => 15000,
            'biaya_administrasi' => 4000,
            'biaya_pemeliharaan' => 2500,
            'is_active' => true,
            'urutan' => 1,
        ]);

        // 4. TNI/POLRI
        $tniPolri = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'TNI-POLRI',
            'nama_golongan' => 'TNI/POLRI',
            'deskripsi' => 'Golongan pelanggan untuk TNI dan POLRI',
            'is_active' => true,
            'urutan' => 4,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $tniPolri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'TNI',
            'nama_sub_golongan' => 'TNI',
            'deskripsi' => 'Tentara Nasional Indonesia',
            'tarif_dasar' => 20000,
            'tarif_per_m3' => 2800,
            'batas_minimum_m3' => 12,
            'tarif_progresif_1' => 3200,
            'tarif_progresif_2' => 3600,
            'tarif_progresif_3' => 4000,
            'biaya_beban_tetap' => 10000,
            'biaya_administrasi' => 3000,
            'biaya_pemeliharaan' => 2000,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $tniPolri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'POLRI',
            'nama_sub_golongan' => 'POLRI',
            'deskripsi' => 'Kepolisian Negara Republik Indonesia',
            'tarif_dasar' => 20000,
            'tarif_per_m3' => 2800,
            'batas_minimum_m3' => 12,
            'tarif_progresif_1' => 3200,
            'tarif_progresif_2' => 3600,
            'tarif_progresif_3' => 4000,
            'biaya_beban_tetap' => 10000,
            'biaya_administrasi' => 3000,
            'biaya_pemeliharaan' => 2000,
            'is_active' => true,
            'urutan' => 2,
        ]);

        // 5. NIAGA
        $niaga = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'NGA',
            'nama_golongan' => 'Niaga',
            'deskripsi' => 'Golongan pelanggan untuk keperluan niaga/komersial',
            'is_active' => true,
            'urutan' => 5,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $niaga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'NGA-KC',
            'nama_sub_golongan' => 'Niaga Kecil',
            'deskripsi' => 'Toko, warung, usaha kecil menengah',
            'tarif_dasar' => 35000,
            'tarif_per_m3' => 5000,
            'batas_minimum_m3' => 15,
            'tarif_progresif_1' => 5500,
            'tarif_progresif_2' => 6000,
            'tarif_progresif_3' => 6500,
            'biaya_beban_tetap' => 18000,
            'biaya_administrasi' => 4500,
            'biaya_pemeliharaan' => 3000,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $niaga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'NGA-BS',
            'nama_sub_golongan' => 'Niaga Besar',
            'deskripsi' => 'Mall, hotel, restoran besar, perkantoran',
            'tarif_dasar' => 60000,
            'tarif_per_m3' => 7000,
            'batas_minimum_m3' => 25,
            'tarif_progresif_1' => 7500,
            'tarif_progresif_2' => 8000,
            'tarif_progresif_3' => 8500,
            'biaya_beban_tetap' => 25000,
            'biaya_administrasi' => 6000,
            'biaya_pemeliharaan' => 4000,
            'is_active' => true,
            'urutan' => 2,
        ]);

        // 6. INDUSTRI
        $industri = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'IND',
            'nama_golongan' => 'Industri',
            'deskripsi' => 'Golongan pelanggan untuk keperluan industri',
            'is_active' => true,
            'urutan' => 6,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $industri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'IND-KC',
            'nama_sub_golongan' => 'Industri Kecil',
            'deskripsi' => 'Industri rumahan, UKM, industri kecil',
            'tarif_dasar' => 45000,
            'tarif_per_m3' => 6000,
            'batas_minimum_m3' => 20,
            'tarif_progresif_1' => 6500,
            'tarif_progresif_2' => 7000,
            'tarif_progresif_3' => 7500,
            'biaya_beban_tetap' => 20000,
            'biaya_administrasi' => 5000,
            'biaya_pemeliharaan' => 3500,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $industri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'IND-BS',
            'nama_sub_golongan' => 'Industri Besar',
            'deskripsi' => 'Pabrik, industri manufaktur, industri besar',
            'tarif_dasar' => 100000,
            'tarif_per_m3' => 10000,
            'batas_minimum_m3' => 50,
            'tarif_progresif_1' => 11000,
            'tarif_progresif_2' => 12000,
            'tarif_progresif_3' => 13000,
            'biaya_beban_tetap' => 40000,
            'biaya_administrasi' => 8000,
            'biaya_pemeliharaan' => 6000,
            'is_active' => true,
            'urutan' => 2,
        ]);
    }
}
