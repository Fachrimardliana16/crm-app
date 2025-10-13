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
            ['kode_kecamatan' => 'PBG01', 'nama_kecamatan' => 'Purbalingga'],
            ['kode_kecamatan' => 'PBG02', 'nama_kecamatan' => 'Kalimanah'],
            ['kode_kecamatan' => 'PBG03', 'nama_kecamatan' => 'Padamara'],
            ['kode_kecamatan' => 'PBG04', 'nama_kecamatan' => 'Pengadegan'],
            ['kode_kecamatan' => 'PBG05', 'nama_kecamatan' => 'Kemangkon'],
            ['kode_kecamatan' => 'PBG06', 'nama_kecamatan' => 'Bukateja'],
            ['kode_kecamatan' => 'PBG07', 'nama_kecamatan' => 'Kejobong'],
            ['kode_kecamatan' => 'PBG08', 'nama_kecamatan' => 'Karangreja'],
            ['kode_kecamatan' => 'PBG09', 'nama_kecamatan' => 'Karangmoncol'],
            ['kode_kecamatan' => 'PBG10', 'nama_kecamatan' => 'Rembang'],
            ['kode_kecamatan' => 'PBG11', 'nama_kecamatan' => 'Bojongsari'],
            ['kode_kecamatan' => 'PBG12', 'nama_kecamatan' => 'Kaligondang'],
            ['kode_kecamatan' => 'PBG13', 'nama_kecamatan' => 'Kutasari'],
            ['kode_kecamatan' => 'PBG14', 'nama_kecamatan' => 'Mrebet'],
            ['kode_kecamatan' => 'PBG15', 'nama_kecamatan' => 'Bobotsari'],
            ['kode_kecamatan' => 'PBG16', 'nama_kecamatan' => 'Karangjambu'],
            ['kode_kecamatan' => 'PBG17', 'nama_kecamatan' => 'Karanganyar'],
            ['kode_kecamatan' => 'PBG18', 'nama_kecamatan' => 'Kertanegara'],
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
