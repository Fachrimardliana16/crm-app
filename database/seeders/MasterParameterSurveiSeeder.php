<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterParameterSurveiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Master Luas Tanah
        DB::table('master_luas_tanah')->insert([
            ['kode' => '0-100', 'nama' => '0 - 100 m²', 'range_min' => '0', 'range_max' => '100', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '100-200', 'nama' => '100 - 200 m²', 'range_min' => '100', 'range_max' => '200', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '200-300', 'nama' => '200 - 300 m²', 'range_min' => '200', 'range_max' => '300', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '300-500', 'nama' => '300 - 500 m²', 'range_min' => '300', 'range_max' => '500', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '>500', 'nama' => 'Lebih dari 500 m²', 'range_min' => '500', 'range_max' => null, 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Luas Bangunan
        DB::table('master_luas_bangunan')->insert([
            ['kode' => '0-60', 'nama' => '0 - 60 m²', 'range_min' => '0', 'range_max' => '60', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '60-120', 'nama' => '60 - 120 m²', 'range_min' => '60', 'range_max' => '120', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '120-200', 'nama' => '120 - 200 m²', 'range_min' => '120', 'range_max' => '200', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '200-300', 'nama' => '200 - 300 m²', 'range_min' => '200', 'range_max' => '300', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '>300', 'nama' => 'Lebih dari 300 m²', 'range_min' => '300', 'range_max' => null, 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Lokasi Bangunan
        DB::table('master_lokasi_bangunan')->insert([
            ['kode' => 'gang-sempit', 'nama' => 'Gang Sempit (< 2 meter)', 'deskripsi' => 'Akses jalan sangat sempit', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'gang-sedang', 'nama' => 'Gang Sedang (2-4 meter)', 'deskripsi' => 'Akses jalan sedang', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'tepi-jalan-kecil', 'nama' => 'Tepi Jalan Kecil (4-6 meter)', 'deskripsi' => 'Di tepi jalan kecil', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'tepi-jalan-besar', 'nama' => 'Tepi Jalan Besar (> 6 meter)', 'deskripsi' => 'Di tepi jalan besar', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'jalan-utama', 'nama' => 'Jalan Utama/Protokol', 'deskripsi' => 'Di jalan utama atau protokol', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Dinding Bangunan
        DB::table('master_dinding_bangunan')->insert([
            ['kode' => 'bambu-kayu', 'nama' => 'Bambu/Kayu/Papan', 'deskripsi' => 'Dinding dari bambu atau kayu', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'tembok-setengah', 'nama' => 'Tembok Setengah Bata', 'deskripsi' => 'Tembok setengah bata', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'tembok-penuh', 'nama' => 'Tembok Bata Penuh', 'deskripsi' => 'Tembok bata penuh', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'batako-hebel', 'nama' => 'Batako/Hebel', 'deskripsi' => 'Dinding batako atau hebel', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'beton-bertulang', 'nama' => 'Beton Bertulang', 'deskripsi' => 'Dinding beton bertulang', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Lantai Bangunan
        DB::table('master_lantai_bangunan')->insert([
            ['kode' => 'tanah', 'nama' => 'Tanah', 'deskripsi' => 'Lantai tanah', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'semen-plester', 'nama' => 'Semen/Plester', 'deskripsi' => 'Lantai semen atau plester', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'keramik-teraso', 'nama' => 'Keramik/Teraso', 'deskripsi' => 'Lantai keramik atau teraso', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'granit-marmer', 'nama' => 'Granit/Marmer', 'deskripsi' => 'Lantai granit atau marmer', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'parket-kayu', 'nama' => 'Parket/Kayu Jati', 'deskripsi' => 'Lantai parket atau kayu jati', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Atap Bangunan
        DB::table('master_atap_bangunan')->insert([
            ['kode' => 'rumbia-jerami', 'nama' => 'Rumbia/Jerami/Daun', 'deskripsi' => 'Atap rumbia, jerami atau daun', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'seng-gelombang', 'nama' => 'Seng Gelombang', 'deskripsi' => 'Atap seng gelombang', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'genteng-tanah', 'nama' => 'Genteng Tanah Liat', 'deskripsi' => 'Atap genteng tanah liat', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'genteng-beton', 'nama' => 'Genteng Beton/Metal', 'deskripsi' => 'Atap genteng beton atau metal', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'dak-beton', 'nama' => 'Dak Beton/Cor', 'deskripsi' => 'Atap dak beton atau cor', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Pagar Bangunan
        DB::table('master_pagar_bangunan')->insert([
            ['kode' => 'tidak-ada', 'nama' => 'Tidak Ada Pagar', 'deskripsi' => 'Tidak memiliki pagar', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'bambu-kayu', 'nama' => 'Bambu/Kayu', 'deskripsi' => 'Pagar bambu atau kayu', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'kawat-seng', 'nama' => 'Kawat/Seng', 'deskripsi' => 'Pagar kawat atau seng', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'tembok-setengah', 'nama' => 'Tembok Setengah', 'deskripsi' => 'Pagar tembok setengah', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'tembok-penuh', 'nama' => 'Tembok Penuh/Besi', 'deskripsi' => 'Pagar tembok penuh atau besi', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Kondisi Jalan
        DB::table('master_kondisi_jalan')->insert([
            ['kode' => 'tanah-becek', 'nama' => 'Tanah/Becek', 'deskripsi' => 'Jalan tanah atau becek', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'kerikil-makadam', 'nama' => 'Kerikil/Makadam', 'deskripsi' => 'Jalan kerikil atau makadam', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'paving-conblock', 'nama' => 'Paving/Conblock', 'deskripsi' => 'Jalan paving atau conblock', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'aspal-hotmix', 'nama' => 'Aspal/Hotmix', 'deskripsi' => 'Jalan aspal atau hotmix', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'beton-cor', 'nama' => 'Beton Cor', 'deskripsi' => 'Jalan beton cor', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Daya Listrik
        DB::table('master_daya_listrik')->insert([
            ['kode' => 'non-pln', 'nama' => 'Non PLN/Tidak Ada', 'range_min' => '0', 'range_max' => '0', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '450-900', 'nama' => '450 - 900 VA', 'range_min' => '450', 'range_max' => '900', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '1300-2200', 'nama' => '1.300 - 2.200 VA', 'range_min' => '1300', 'range_max' => '2200', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '3500-5500', 'nama' => '3.500 - 5.500 VA', 'range_min' => '3500', 'range_max' => '5500', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '>5500', 'nama' => 'Lebih dari 5.500 VA', 'range_min' => '5500', 'range_max' => null, 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Fungsi Rumah
        DB::table('master_fungsi_rumah')->insert([
            ['kode' => 'kontrakan-kos', 'nama' => 'Kontrakan/Kos-kosan', 'deskripsi' => 'Rumah kontrakan atau kos-kosan', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'rumah-sederhana', 'nama' => 'Rumah Sederhana', 'deskripsi' => 'Rumah sederhana', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'rumah-menengah', 'nama' => 'Rumah Menengah', 'deskripsi' => 'Rumah menengah', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'rumah-mewah', 'nama' => 'Rumah Mewah', 'deskripsi' => 'Rumah mewah', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'rumah-sangat-mewah', 'nama' => 'Rumah Sangat Mewah', 'deskripsi' => 'Rumah sangat mewah', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Master Kepemilikan Kendaraan
        DB::table('master_kepemilikan_kendaraan')->insert([
            ['kode' => 'tidak-ada', 'nama' => 'Tidak Ada Kendaraan', 'deskripsi' => 'Tidak memiliki kendaraan', 'skor' => 2, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'sepeda-becak', 'nama' => 'Sepeda/Becak', 'deskripsi' => 'Memiliki sepeda atau becak', 'skor' => 4, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'motor-1', 'nama' => 'Sepeda Motor (1 unit)', 'deskripsi' => 'Memiliki 1 sepeda motor', 'skor' => 6, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'motor-mobil', 'nama' => 'Motor + Mobil', 'deskripsi' => 'Memiliki motor dan mobil', 'skor' => 8, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'mobil-multiple', 'nama' => 'Mobil Multiple/Mewah', 'deskripsi' => 'Memiliki mobil lebih dari 1 atau mewah', 'skor' => 10, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
