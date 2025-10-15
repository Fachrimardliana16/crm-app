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
            'biaya_tetap_subgolongan' => 11000,
            'tarif_blok_1' => 850,  // 0-10 m³
            'tarif_blok_2' => 1140, // 11-20 m³
            'tarif_blok_3' => 1550, // 21-30 m³
            'tarif_blok_4' => 2090, // >30 m³
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $sosial->id_golongan_pelanggan,
            'kode_sub_golongan' => 'SOC-HU',
            'nama_sub_golongan' => 'Sosial Umum (HU)',
            'deskripsi' => 'Sekolah, puskesmas, fasilitas umum lainnya',
            'biaya_tetap_subgolongan' => 10000,
            'tarif_blok_1' => 780,  // 0-10 m³
            'tarif_blok_2' => 780,  // 11-20 m³
            'tarif_blok_3' => 780,  // 21-30 m³
            'tarif_blok_4' => 780,  // >30 m³
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
            'biaya_tetap_subgolongan' => 11500,
            'tarif_blok_1' => 1100, // 0-10 m³
            'tarif_blok_2' => 1480, // 11-20 m³
            'tarif_blok_3' => 2000, // 21-30 m³
            'tarif_blok_4' => 2700, // >30 m³
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-B',
            'nama_sub_golongan' => 'Rumah Tangga B',
            'deskripsi' => 'Rumah tangga kategori B (11-20m³)',
            'biaya_tetap_subgolongan' => 12000,
            'tarif_blok_1' => 1190, // 0-10 m³
            'tarif_blok_2' => 1600, // 11-20 m³
            'tarif_blok_3' => 2160, // 21-30 m³
            'tarif_blok_4' => 2930, // >30 m³
            'is_active' => true,
            'urutan' => 2,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-C',
            'nama_sub_golongan' => 'Rumah Tangga C',
            'deskripsi' => 'Rumah tangga kategori C (>20m³)',
            'biaya_tetap_subgolongan' => 15000,
            'tarif_blok_1' => 1280, // 0-10 m³
            'tarif_blok_2' => 1730, // 11-20 m³
            'tarif_blok_3' => 2340, // 21-30 m³
            'tarif_blok_4' => 3160, // >30 m³
            'is_active' => true,
            'urutan' => 3,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-KH',
            'nama_sub_golongan' => 'Rumah Tangga Khusus',
            'deskripsi' => 'Rumah tangga dengan kebutuhan khusus',
            'biaya_tetap_subgolongan' => 11500,
            'tarif_blok_1' => 990,  // 0-10 m³
            'tarif_blok_2' => 1300, // 11-20 m³
            'tarif_blok_3' => 1800, // 21-30 m³
            'tarif_blok_4' => 2400, // >30 m³
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
            'biaya_tetap_subgolongan' => 20000,
            'tarif_blok_1' => 1380, // 0-10 m³
            'tarif_blok_2' => 1850, // 11-20 m³
            'tarif_blok_3' => 2500, // 21-30 m³
            'tarif_blok_4' => 3380, // >30 m³
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
            'biaya_tetap_subgolongan' => 40000,
            'tarif_blok_1' => 1380, // 0-10 m³
            'tarif_blok_2' => 1850, // 11-20 m³
            'tarif_blok_3' => 2500, // 21-30 m³
            'tarif_blok_4' => 3380, // >30 m³
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $tniPolri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'POLRI',
            'nama_sub_golongan' => 'POLRI',
            'deskripsi' => 'Kepolisian Negara Republik Indonesia',
            'biaya_tetap_subgolongan' => 40000,
            'tarif_blok_1' => 1380, // 0-10 m³
            'tarif_blok_2' => 1850, // 11-20 m³
            'tarif_blok_3' => 2500, // 21-30 m³
            'tarif_blok_4' => 3380, // >30 m³
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
            'biaya_tetap_subgolongan' => 23000,
            'tarif_blok_1' => 1840, // 0-10 m³
            'tarif_blok_2' => 2480, // 11-20 m³
            'tarif_blok_3' => 3350, // 21-30 m³
            'tarif_blok_4' => 4530, // >30 m³
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $niaga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'NGA-BS',
            'nama_sub_golongan' => 'Niaga Besar',
            'deskripsi' => 'Mall, hotel, restoran besar, perkantoran',
            'biaya_tetap_subgolongan' => 26000,
            'tarif_blok_1' => 2060, // 0-10 m³
            'tarif_blok_2' => 2920, // 11-20 m³
            'tarif_blok_3' => 3950, // 21-30 m³
            'tarif_blok_4' => 5160, // >30 m³
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
            'biaya_tetap_subgolongan' => 28000,
            'tarif_blok_1' => 2060, // 0-10 m³
            'tarif_blok_2' => 2920, // 11-20 m³
            'tarif_blok_3' => 3950, // 21-30 m³
            'tarif_blok_4' => 5160, // >30 m³
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $industri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'IND-BS',
            'nama_sub_golongan' => 'Industri Besar',
            'deskripsi' => 'Pabrik, industri manufaktur, industri besar',
            'biaya_tetap_subgolongan' => 34000,
            'tarif_blok_1' => 2290, // 0-10 m³
            'tarif_blok_2' => 3100, // 11-20 m³
            'tarif_blok_3' => 4190, // 21-30 m³
            'tarif_blok_4' => 5650, // >30 m³
            'is_active' => true,
            'urutan' => 2,
        ]);
    }
}
