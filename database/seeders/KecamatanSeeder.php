<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan activity logging untuk seeder
        activity()->disableLogging();

        $kecamatanData = [
            // Kecamatan di Kabupaten Purbalingga
            ['kode_kecamatan' => 'PBG01', 'nama_kecamatan' => 'Purbalingga', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG02', 'nama_kecamatan' => 'Kalimanah', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG03', 'nama_kecamatan' => 'Padamara', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG04', 'nama_kecamatan' => 'Pengadegan', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG05', 'nama_kecamatan' => 'Kemangkon', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG06', 'nama_kecamatan' => 'Bukateja', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG07', 'nama_kecamatan' => 'Kejobong', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG08', 'nama_kecamatan' => 'Karangreja', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG09', 'nama_kecamatan' => 'Karangmoncol', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG10', 'nama_kecamatan' => 'Rembang', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG11', 'nama_kecamatan' => 'Bojongsari', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG12', 'nama_kecamatan' => 'Kaligondang', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG13', 'nama_kecamatan' => 'Kutasari', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG14', 'nama_kecamatan' => 'Mrebet', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG15', 'nama_kecamatan' => 'Bobotsari', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG16', 'nama_kecamatan' => 'Karangjambu', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG17', 'nama_kecamatan' => 'Karanganyar', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
            ['kode_kecamatan' => 'PBG18', 'nama_kecamatan' => 'Kertanegara', 'kota' => 'Kabupaten Purbalingga', 'provinsi' => 'Jawa Tengah'],
        ];

        // Insert atau update data dengan upsert
        foreach ($kecamatanData as $data) {
            Kecamatan::updateOrCreate(
                ['kode_kecamatan' => $data['kode_kecamatan']],
                $data
            );
        }

        // Aktifkan kembali activity logging
        activity()->enableLogging();
    }
}
