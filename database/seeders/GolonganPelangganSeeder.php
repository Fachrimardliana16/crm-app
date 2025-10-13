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
        // Sosial
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
            'kode_sub_golongan' => 'SOC-UM',
            'nama_sub_golongan' => 'Sosial Umum',
            'deskripsi' => 'Rumah ibadah, sekolah, puskesmas',
            'tarif_dasar' => 15000,
            'tarif_per_m3' => 2500,
            'batas_minimum_m3' => 10,
            'tarif_progresif_1' => 3000,
            'tarif_progresif_2' => 3500,
            'tarif_progresif_3' => 4000,
            'biaya_beban_tetap' => 5000,
            'biaya_administrasi' => 2000,
            'biaya_pemeliharaan' => 1000,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $sosial->id_golongan_pelanggan,
            'kode_sub_golongan' => 'SOC-KH',
            'nama_sub_golongan' => 'Sosial Khusus',
            'deskripsi' => 'Instalasi khusus sosial, panti asuhan',
            'tarif_dasar' => 12000,
            'tarif_per_m3' => 2000,
            'batas_minimum_m3' => 8,
            'tarif_progresif_1' => 2500,
            'tarif_progresif_2' => 3000,
            'tarif_progresif_3' => 3500,
            'biaya_beban_tetap' => 3000,
            'biaya_administrasi' => 1500,
            'biaya_pemeliharaan' => 500,
            'is_active' => true,
            'urutan' => 2,
        ]);

        // Komersial
        $komersial = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'KOM',
            'nama_golongan' => 'Komersial',
            'deskripsi' => 'Golongan pelanggan untuk keperluan komersial',
            'is_active' => true,
            'urutan' => 2,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $komersial->id_golongan_pelanggan,
            'kode_sub_golongan' => 'KOM-KC',
            'nama_sub_golongan' => 'Komersial Kecil',
            'deskripsi' => 'Toko, warung, usaha kecil',
            'tarif_dasar' => 25000,
            'tarif_per_m3' => 4000,
            'batas_minimum_m3' => 15,
            'tarif_progresif_1' => 5000,
            'tarif_progresif_2' => 6000,
            'tarif_progresif_3' => 7000,
            'biaya_beban_tetap' => 10000,
            'biaya_administrasi' => 3000,
            'biaya_pemeliharaan' => 2000,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $komersial->id_golongan_pelanggan,
            'kode_sub_golongan' => 'KOM-BS',
            'nama_sub_golongan' => 'Komersial Besar',
            'deskripsi' => 'Mall, hotel, restoran besar',
            'tarif_dasar' => 50000,
            'tarif_per_m3' => 6000,
            'batas_minimum_m3' => 25,
            'tarif_progresif_1' => 7500,
            'tarif_progresif_2' => 9000,
            'tarif_progresif_3' => 10500,
            'biaya_beban_tetap' => 20000,
            'biaya_administrasi' => 5000,
            'biaya_pemeliharaan' => 3000,
            'is_active' => true,
            'urutan' => 2,
        ]);

        // Industri
        $industri = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'IND',
            'nama_golongan' => 'Industri',
            'deskripsi' => 'Golongan pelanggan untuk keperluan industri',
            'is_active' => true,
            'urutan' => 3,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $industri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'IND-KC',
            'nama_sub_golongan' => 'Industri Kecil',
            'deskripsi' => 'Industri rumahan, UKM',
            'tarif_dasar' => 35000,
            'tarif_per_m3' => 5000,
            'batas_minimum_m3' => 20,
            'tarif_progresif_1' => 6000,
            'tarif_progresif_2' => 7000,
            'tarif_progresif_3' => 8000,
            'biaya_beban_tetap' => 15000,
            'biaya_administrasi' => 4000,
            'biaya_pemeliharaan' => 2500,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $industri->id_golongan_pelanggan,
            'kode_sub_golongan' => 'IND-BS',
            'nama_sub_golongan' => 'Industri Besar',
            'deskripsi' => 'Pabrik, industri manufaktur',
            'tarif_dasar' => 75000,
            'tarif_per_m3' => 8000,
            'batas_minimum_m3' => 50,
            'tarif_progresif_1' => 10000,
            'tarif_progresif_2' => 12000,
            'tarif_progresif_3' => 15000,
            'biaya_beban_tetap' => 30000,
            'biaya_administrasi' => 7500,
            'biaya_pemeliharaan' => 5000,
            'is_active' => true,
            'urutan' => 2,
        ]);

        // Rumah Tangga
        $rumahTangga = GolonganPelanggan::create([
            'id_golongan_pelanggan' => (string) Str::uuid(),
            'kode_golongan' => 'RT',
            'nama_golongan' => 'Rumah Tangga',
            'deskripsi' => 'Golongan pelanggan untuk keperluan rumah tangga',
            'is_active' => true,
            'urutan' => 4,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-1',
            'nama_sub_golongan' => 'Rumah Tangga I',
            'deskripsi' => 'Pelanggan rumah tangga kategori I (0-20m³)',
            'tarif_dasar' => 10000,
            'tarif_per_m3' => 1500,
            'batas_minimum_m3' => 10,
            'tarif_progresif_1' => 2000,
            'tarif_progresif_2' => 2500,
            'tarif_progresif_3' => 3000,
            'biaya_beban_tetap' => 7500,
            'biaya_administrasi' => 2500,
            'biaya_pemeliharaan' => 1500,
            'is_active' => true,
            'urutan' => 1,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-2',
            'nama_sub_golongan' => 'Rumah Tangga II',
            'deskripsi' => 'Pelanggan rumah tangga kategori II (21-30m³)',
            'tarif_dasar' => 15000,
            'tarif_per_m3' => 2000,
            'batas_minimum_m3' => 15,
            'tarif_progresif_1' => 2500,
            'tarif_progresif_2' => 3000,
            'tarif_progresif_3' => 3500,
            'biaya_beban_tetap' => 10000,
            'biaya_administrasi' => 3000,
            'biaya_pemeliharaan' => 2000,
            'is_active' => true,
            'urutan' => 2,
        ]);

        SubGolonganPelanggan::create([
            'id_sub_golongan_pelanggan' => (string) Str::uuid(),
            'id_golongan_pelanggan' => $rumahTangga->id_golongan_pelanggan,
            'kode_sub_golongan' => 'RT-3',
            'nama_sub_golongan' => 'Rumah Tangga III',
            'deskripsi' => 'Pelanggan rumah tangga kategori III (>30m³)',
            'tarif_dasar' => 20000,
            'tarif_per_m3' => 3000,
            'batas_minimum_m3' => 20,
            'tarif_progresif_1' => 3500,
            'tarif_progresif_2' => 4000,
            'tarif_progresif_3' => 4500,
            'biaya_beban_tetap' => 12500,
            'biaya_administrasi' => 3500,
            'biaya_pemeliharaan' => 2500,
            'is_active' => true,
            'urutan' => 3,
        ]);
    }
}
