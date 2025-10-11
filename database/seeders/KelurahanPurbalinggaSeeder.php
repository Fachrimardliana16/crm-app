<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelurahan;
use App\Models\Kecamatan;

class KelurahanPurbalinggaSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan activity logging untuk seeder
        activity()->disableLogging();

        // Ambil ID kecamatan berdasarkan kode
        $kecamatanMap = Kecamatan::pluck('id_kecamatan', 'kode_kecamatan')->toArray();

        $kelurahanData = [
            // Kecamatan Purbalingga (PBG01) - 4 Kelurahan
            ['kode_kelurahan' => 'PBG01001', 'nama_kelurahan' => 'Purbalingga Lor', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53311'],
            ['kode_kelurahan' => 'PBG01002', 'nama_kelurahan' => 'Purbalingga Kidul', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53312'],
            ['kode_kelurahan' => 'PBG01003', 'nama_kelurahan' => 'Purbalingga Wetan', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53313'],
            ['kode_kelurahan' => 'PBG01004', 'nama_kelurahan' => 'Purbalingga Kulon', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53314'],

            // Kecamatan Kalimanah (PBG02) - 7 Kelurahan
            ['kode_kelurahan' => 'PBG02001', 'nama_kelurahan' => 'Kalimanah Kulon', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => 'PBG02002', 'nama_kelurahan' => 'Kalimanah Wetan', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => 'PBG02003', 'nama_kelurahan' => 'Winduaji', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53373'],
            ['kode_kelurahan' => 'PBG02004', 'nama_kelurahan' => 'Lebeng', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53374'],
            ['kode_kelurahan' => 'PBG02005', 'nama_kelurahan' => 'Karangjambu', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53375'],
            ['kode_kelurahan' => 'PBG02006', 'nama_kelurahan' => 'Gumiwang', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53376'],
            ['kode_kelurahan' => 'PBG02007', 'nama_kelurahan' => 'Pekuncen', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53377'],

            // Kecamatan Padamara (PBG03) - 9 Desa
            ['kode_kelurahan' => 'PBG03001', 'nama_kelurahan' => 'Padamara', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => 'PBG03002', 'nama_kelurahan' => 'Gumelem Kulon', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => 'PBG03003', 'nama_kelurahan' => 'Gumelem Wetan', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53383'],
            ['kode_kelurahan' => 'PBG03004', 'nama_kelurahan' => 'Karanglo', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53384'],
            ['kode_kelurahan' => 'PBG03005', 'nama_kelurahan' => 'Karangdadap', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53385'],
            ['kode_kelurahan' => 'PBG03006', 'nama_kelurahan' => 'Padamanik', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53386'],
            ['kode_kelurahan' => 'PBG03007', 'nama_kelurahan' => 'Karangcegak', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53387'],
            ['kode_kelurahan' => 'PBG03008', 'nama_kelurahan' => 'Pamijen', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53388'],
            ['kode_kelurahan' => 'PBG03009', 'nama_kelurahan' => 'Tambaksogra', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53389'],

            // Kecamatan Pengadegan (PBG04) - 11 Desa
            ['kode_kelurahan' => 'PBG04001', 'nama_kelurahan' => 'Pengadegan', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => 'PBG04002', 'nama_kelurahan' => 'Tegalsari', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => 'PBG04003', 'nama_kelurahan' => 'Salamerta', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => 'PBG04004', 'nama_kelurahan' => 'Wonoyoso', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53394'],
            ['kode_kelurahan' => 'PBG04005', 'nama_kelurahan' => 'Karanggedang', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53395'],
            ['kode_kelurahan' => 'PBG04006', 'nama_kelurahan' => 'Karangreja', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53396'],
            ['kode_kelurahan' => 'PBG04007', 'nama_kelurahan' => 'Karangduwur', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53397'],
            ['kode_kelurahan' => 'PBG04008', 'nama_kelurahan' => 'Pasir', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53398'],
            ['kode_kelurahan' => 'PBG04009', 'nama_kelurahan' => 'Panggung', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53399'],
            ['kode_kelurahan' => 'PBG04010', 'nama_kelurahan' => 'Margahayu', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53400'],
            ['kode_kelurahan' => 'PBG04011', 'nama_kelurahan' => 'Panembangan', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53401'],

            // Kecamatan Kemangkon (PBG05) - 9 Desa
            ['kode_kelurahan' => 'PBG05001', 'nama_kelurahan' => 'Kemangkon', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53351'],
            ['kode_kelurahan' => 'PBG05002', 'nama_kelurahan' => 'Sokawera', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => 'PBG05003', 'nama_kelurahan' => 'Bantarsari', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => 'PBG05004', 'nama_kelurahan' => 'Karanggedang', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => 'PBG05005', 'nama_kelurahan' => 'Kembaran', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53355'],
            ['kode_kelurahan' => 'PBG05006', 'nama_kelurahan' => 'Tunjungsari', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53356'],
            ['kode_kelurahan' => 'PBG05007', 'nama_kelurahan' => 'Prembun', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53357'],
            ['kode_kelurahan' => 'PBG05008', 'nama_kelurahan' => 'Watuagung', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => 'PBG05009', 'nama_kelurahan' => 'Karangpucung', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53359'],
        ];

        foreach ($kelurahanData as $data) {
            if ($data['id_kecamatan']) {
                Kelurahan::updateOrCreate(
                    ['kode_kelurahan' => $data['kode_kelurahan']],
                    $data
                );
            }
        }

        // Aktifkan kembali activity logging
        activity()->enableLogging();
    }
}
