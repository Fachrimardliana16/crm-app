<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelurahan;
use App\Models\Kecamatan;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan activity logging untuk seeder
        activity()->disableLogging();

        // Ambil ID kecamatan berdasarkan kode
        $kecamatanMap = Kecamatan::pluck('id_kecamatan', 'kode_kecamatan')->toArray();

        $kelurahanData = [
            // Kecamatan Purbalingga (PBG01) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303060001', 'nama_kelurahan' => 'Bojong', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53311'],
            ['kode_kelurahan' => '3303060002', 'nama_kelurahan' => 'Toyareja', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53312'],
            ['kode_kelurahan' => '3303060003', 'nama_kelurahan' => 'Kedung Menjangan', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53313'],
            ['kode_kelurahan' => '3303060004', 'nama_kelurahan' => 'Jatisaba', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53314'],
            ['kode_kelurahan' => '3303060005', 'nama_kelurahan' => 'Bancar', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53315'],
            ['kode_kelurahan' => '3303060006', 'nama_kelurahan' => 'Purbalingga Wetan', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53316'],
            ['kode_kelurahan' => '3303060007', 'nama_kelurahan' => 'Penambongan', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53317'],
            ['kode_kelurahan' => '3303060008', 'nama_kelurahan' => 'Purbalingga Kidul', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53318'],
            ['kode_kelurahan' => '3303060009', 'nama_kelurahan' => 'Kandang Gampang', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53319'],
            ['kode_kelurahan' => '3303060010', 'nama_kelurahan' => 'Purbalingga Kulon', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53320'],
            ['kode_kelurahan' => '3303060011', 'nama_kelurahan' => 'Purbalingga Lor', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53321'],
            ['kode_kelurahan' => '3303060012', 'nama_kelurahan' => 'Kembaran Kulon', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53322'],
            ['kode_kelurahan' => '3303060013', 'nama_kelurahan' => 'Wirasana', 'id_kecamatan' => $kecamatanMap['PBG01'] ?? null, 'kode_pos' => '53323'],

            // Data yang hilang dari daftar 10 desa Anda (Total 19 desa)
            ['kode_kelurahan' => '3303010011', 'nama_kelurahan' => 'Gambarsari', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010012', 'nama_kelurahan' => 'Jetis', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010013', 'nama_kelurahan' => 'Kalialang', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010014', 'nama_kelurahan' => 'Karangtengah', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010015', 'nama_kelurahan' => 'Majasem', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010016', 'nama_kelurahan' => 'Muntang', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010017', 'nama_kelurahan' => 'Senon', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010018', 'nama_kelurahan' => 'Sumilir', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010019', 'nama_kelurahan' => 'Toyareka', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010001', 'nama_kelurahan' => 'Kedungbenda', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010002', 'nama_kelurahan' => 'Bokol', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010003', 'nama_kelurahan' => 'Pelumutan', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010004', 'nama_kelurahan' => 'Majatengah', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010005', 'nama_kelurahan' => 'Kedunglegok', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010006', 'nama_kelurahan' => 'Kemangkon', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010007', 'nama_kelurahan' => 'Panican', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010008', 'nama_kelurahan' => 'Bakulan', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010009', 'nama_kelurahan' => 'Karangkemiri', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],
            ['kode_kelurahan' => '3303010010', 'nama_kelurahan' => 'Pegandekan', 'id_kecamatan' => $kecamatanMap['PBG05'] ?? null, 'kode_pos' => '53381'],

            // Kecamatan Kalimanah (PBG02) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303070001', 'nama_kelurahan' => 'Jompo', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070002', 'nama_kelurahan' => 'Rabak', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070003', 'nama_kelurahan' => 'Blater', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070004', 'nama_kelurahan' => 'Sidakangen', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070005', 'nama_kelurahan' => 'Karangpetir', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070006', 'nama_kelurahan' => 'Grecol', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070007', 'nama_kelurahan' => 'Kedungwuluh', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'], // Desa yang hilang
            ['kode_kelurahan' => '3303070008', 'nama_kelurahan' => 'Karangmanyar', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070009', 'nama_kelurahan' => 'Kalikabong', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070010', 'nama_kelurahan' => 'Selabaya', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070011', 'nama_kelurahan' => 'Kalimanah Wetan', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070012', 'nama_kelurahan' => 'Kalimanah Kulon', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070013', 'nama_kelurahan' => 'Manduraga', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070014', 'nama_kelurahan' => 'Karangsari', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070015', 'nama_kelurahan' => 'Mewek', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'], // Kelurahan yang hilang
            ['kode_kelurahan' => '3303070016', 'nama_kelurahan' => 'Klapasawit', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],
            ['kode_kelurahan' => '3303070017', 'nama_kelurahan' => 'Babakan', 'id_kecamatan' => $kecamatanMap['PBG02'] ?? null, 'kode_pos' => '53371'],

            // Kecamatan Bukateja (PBG06) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303020001', 'nama_kelurahan' => 'Tidu', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020002', 'nama_kelurahan' => 'Wirasaba', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020003', 'nama_kelurahan' => 'Kembangan', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020004', 'nama_kelurahan' => 'Cipawon', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020005', 'nama_kelurahan' => 'Karangcengis', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020006', 'nama_kelurahan' => 'Karanggedang', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020007', 'nama_kelurahan' => 'Karangnangka', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020008', 'nama_kelurahan' => 'Kutawis', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020009', 'nama_kelurahan' => 'Bukateja', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020010', 'nama_kelurahan' => 'Penaruban', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020011', 'nama_kelurahan' => 'Kedungjati', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020012', 'nama_kelurahan' => 'Kebutuh', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020013', 'nama_kelurahan' => 'Majasari', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],
            ['kode_kelurahan' => '3303020014', 'nama_kelurahan' => 'Bajong', 'id_kecamatan' => $kecamatanMap['PBG06'] ?? null, 'kode_pos' => '53382'],

            // Kecamatan Kejobong (PBG07) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303030001', 'nama_kelurahan' => 'Bandingan', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030002', 'nama_kelurahan' => 'Lamuk', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030003', 'nama_kelurahan' => 'Sokanegara', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030004', 'nama_kelurahan' => 'Gumiwang', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030005', 'nama_kelurahan' => 'Kedarpan', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030006', 'nama_kelurahan' => 'Nangkasawit', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030007', 'nama_kelurahan' => 'Pandansari', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030008', 'nama_kelurahan' => 'Kejobong', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030009', 'nama_kelurahan' => 'Langgar', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030010', 'nama_kelurahan' => 'Timbang', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030011', 'nama_kelurahan' => 'Nangkod', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030012', 'nama_kelurahan' => 'Krenceng', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],
            ['kode_kelurahan' => '3303030013', 'nama_kelurahan' => 'Pangempon', 'id_kecamatan' => $kecamatanMap['PBG07'] ?? null, 'kode_pos' => '53392'],

            // Kecamatan Pengadegan (PBG04) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303040001', 'nama_kelurahan' => 'Pasunggingan', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040002', 'nama_kelurahan' => 'Pengadegan', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040003', 'nama_kelurahan' => 'Karangjoho', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040004', 'nama_kelurahan' => 'Larangan', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040005', 'nama_kelurahan' => 'Panunggalan', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040006', 'nama_kelurahan' => 'Bedagas', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040007', 'nama_kelurahan' => 'Tumanggal', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040008', 'nama_kelurahan' => 'Tegalpingen', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],
            ['kode_kelurahan' => '3303040009', 'nama_kelurahan' => 'Tetel', 'id_kecamatan' => $kecamatanMap['PBG04'] ?? null, 'kode_pos' => '53393'],

            // Kecamatan Kaligondang (PBG12) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303050001', 'nama_kelurahan' => 'Lamongan', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050002', 'nama_kelurahan' => 'Tejasari', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050003', 'nama_kelurahan' => 'Cilapar', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050004', 'nama_kelurahan' => 'Penolih', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050005', 'nama_kelurahan' => 'Sinduraja', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050006', 'nama_kelurahan' => 'Selakambang', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050007', 'nama_kelurahan' => 'Selanegara', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050008', 'nama_kelurahan' => 'Kaligondang', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050009', 'nama_kelurahan' => 'Kembaran Wetan', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050010', 'nama_kelurahan' => 'Sempor Lor', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050011', 'nama_kelurahan' => 'Arenan', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050012', 'nama_kelurahan' => 'Brecek', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050013', 'nama_kelurahan' => 'Kalikajar', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050014', 'nama_kelurahan' => 'Pagerandong', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050015', 'nama_kelurahan' => 'Penaruban', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050016', 'nama_kelurahan' => 'Sidanegara', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050017', 'nama_kelurahan' => 'Sidareja', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],
            ['kode_kelurahan' => '3303050018', 'nama_kelurahan' => 'Slinga', 'id_kecamatan' => $kecamatanMap['PBG12'] ?? null, 'kode_pos' => '53391'],

            // Kecamatan Padamara (PBG03) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303080001', 'nama_kelurahan' => 'Karangpule', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080002', 'nama_kelurahan' => 'Kalitinggar', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080003', 'nama_kelurahan' => 'Sokawera', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080004', 'nama_kelurahan' => 'Padamara', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080005', 'nama_kelurahan' => 'Karangjambe', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080006', 'nama_kelurahan' => 'Karangsentul', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080007', 'nama_kelurahan' => 'Kalitinggar Kidul', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080008', 'nama_kelurahan' => 'Karanggambas', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080009', 'nama_kelurahan' => 'Gemuruh', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080010', 'nama_kelurahan' => 'Dawuhan', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080011', 'nama_kelurahan' => 'Bojanegara', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080012', 'nama_kelurahan' => 'Mipiran', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080013', 'nama_kelurahan' => 'Prigi', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],
            ['kode_kelurahan' => '3303080014', 'nama_kelurahan' => 'Purbayasa', 'id_kecamatan' => $kecamatanMap['PBG03'] ?? null, 'kode_pos' => '53372'],

            // Kecamatan Kutasari (PBG13) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303090001', 'nama_kelurahan' => 'Karanglewas', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090002', 'nama_kelurahan' => 'Candiwulan', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090003', 'nama_kelurahan' => 'Karangklesem', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090004', 'nama_kelurahan' => 'Kutasari', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090005', 'nama_kelurahan' => 'Sumingkir', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090006', 'nama_kelurahan' => 'Meri', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090007', 'nama_kelurahan' => 'Candinata', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090008', 'nama_kelurahan' => 'Cendana', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090009', 'nama_kelurahan' => 'Karangaren', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090010', 'nama_kelurahan' => 'Karangcegak', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090011', 'nama_kelurahan' => 'Karangjengkol', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090012', 'nama_kelurahan' => 'Karangreja', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090013', 'nama_kelurahan' => 'Limbangan', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],
            ['kode_kelurahan' => '3303090014', 'nama_kelurahan' => 'Munjul', 'id_kecamatan' => $kecamatanMap['PBG13'] ?? null, 'kode_pos' => '53361'],

            // Kecamatan Bojongsari (PBG11) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303140001', 'nama_kelurahan' => 'Brobot', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140002', 'nama_kelurahan' => 'Gembong', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140003', 'nama_kelurahan' => 'Galuh', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140004', 'nama_kelurahan' => 'Banjaran', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140005', 'nama_kelurahan' => 'Patemon', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140006', 'nama_kelurahan' => 'Bojongsari', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140007', 'nama_kelurahan' => 'Kajongan', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140008', 'nama_kelurahan' => 'Karangbanjar', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140009', 'nama_kelurahan' => 'Beji', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140010', 'nama_kelurahan' => 'Pagedangan', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140011', 'nama_kelurahan' => 'Pekalongan', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140012', 'nama_kelurahan' => 'Metenggeng', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],
            ['kode_kelurahan' => '3303140013', 'nama_kelurahan' => 'Bumisari', 'id_kecamatan' => $kecamatanMap['PBG11'] ?? null, 'kode_pos' => '53362'],

            // Kecamatan Mrebet (PBG14) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303080001', 'nama_kelurahan' => 'Karangturi', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080002', 'nama_kelurahan' => 'Onje', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080003', 'nama_kelurahan' => 'Sindang', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080004', 'nama_kelurahan' => 'Tangkisan', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080005', 'nama_kelurahan' => 'Kradenan', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080006', 'nama_kelurahan' => 'Lambur', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080007', 'nama_kelurahan' => 'Selaganggeng', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080008', 'nama_kelurahan' => 'Mangunegara', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080009', 'nama_kelurahan' => 'Karangnangka', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080010', 'nama_kelurahan' => 'Mrebet', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080011', 'nama_kelurahan' => 'Bojong', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080012', 'nama_kelurahan' => 'Serayu Karanganyar', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080013', 'nama_kelurahan' => 'Serayu Larangan', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080014', 'nama_kelurahan' => 'Campakoah', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080015', 'nama_kelurahan' => 'Pagerandong', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080016', 'nama_kelurahan' => 'Cipaku', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080017', 'nama_kelurahan' => 'Binangun', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080018', 'nama_kelurahan' => 'Pengalusan', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],
            ['kode_kelurahan' => '3303080019', 'nama_kelurahan' => 'Sangkanayu', 'id_kecamatan' => $kecamatanMap['PBG14'] ?? null, 'kode_pos' => '53352'],

            // Kecamatan Bobotsari (PBG15) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303090001', 'nama_kelurahan' => 'Gandasuli', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090002', 'nama_kelurahan' => 'Kalapacung', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090003', 'nama_kelurahan' => 'Karangmalang', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090004', 'nama_kelurahan' => 'Banjarsari', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090005', 'nama_kelurahan' => 'Majapura', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090006', 'nama_kelurahan' => 'Bobotsari', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090007', 'nama_kelurahan' => 'Karangduren', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090008', 'nama_kelurahan' => 'Pakuncen', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090009', 'nama_kelurahan' => 'Karangtalun', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090010', 'nama_kelurahan' => 'Gunungkarang', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090011', 'nama_kelurahan' => 'Talagening', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090012', 'nama_kelurahan' => 'Tlagayasa', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090013', 'nama_kelurahan' => 'Dagan', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090014', 'nama_kelurahan' => 'Palumbungan', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090015', 'nama_kelurahan' => 'Limbasari', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],
            ['kode_kelurahan' => '3303090016', 'nama_kelurahan' => 'Palumbungan Wetan', 'id_kecamatan' => $kecamatanMap['PBG15'] ?? null, 'kode_pos' => '53353'],

            // Kecamatan Karangreja (PBG08) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303130001', 'nama_kelurahan' => 'Serang', 'id_kecamatan' => $kecamatanMap['PBG08'] ?? null, 'kode_pos' => '53356'],
            ['kode_kelurahan' => '3303130002', 'nama_kelurahan' => 'Kutabawa', 'id_kecamatan' => $kecamatanMap['PBG08'] ?? null, 'kode_pos' => '53356'],
            ['kode_kelurahan' => '3303130003', 'nama_kelurahan' => 'Siwarak', 'id_kecamatan' => $kecamatanMap['PBG08'] ?? null, 'kode_pos' => '53356'],
            ['kode_kelurahan' => '3303130004', 'nama_kelurahan' => 'Tlahab Kidul', 'id_kecamatan' => $kecamatanMap['PBG08'] ?? null, 'kode_pos' => '53356'],
            ['kode_kelurahan' => '3303130005', 'nama_kelurahan' => 'Gondang', 'id_kecamatan' => $kecamatanMap['PBG08'] ?? null, 'kode_pos' => '53356'],
            ['kode_kelurahan' => '3303130006', 'nama_kelurahan' => 'Karangreja', 'id_kecamatan' => $kecamatanMap['PBG08'] ?? null, 'kode_pos' => '53356'],

            // Kecamatan Rembang (PBG10) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303100001', 'nama_kelurahan' => 'Losari', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100002', 'nama_kelurahan' => 'Wanogara Wetan', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100003', 'nama_kelurahan' => 'Wonogara Kulon', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100004', 'nama_kelurahan' => 'Bantarbarang', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100005', 'nama_kelurahan' => 'Gunungwuled', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100006', 'nama_kelurahan' => 'Karangbawang', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100007', 'nama_kelurahan' => 'Tanalum', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100008', 'nama_kelurahan' => 'Sumampir', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100009', 'nama_kelurahan' => 'Makam', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100010', 'nama_kelurahan' => 'Panusupan', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100011', 'nama_kelurahan' => 'Wlahar', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],
            ['kode_kelurahan' => '3303100013', 'nama_kelurahan' => 'Bodas Karangjati', 'id_kecamatan' => $kecamatanMap['PBG10'] ?? null, 'kode_pos' => '53358'],

            // Kecamatan Karanganyar (PBG17) - Data dari API wilayah Indonesia
            ['kode_kelurahan' => '3303110001', 'nama_kelurahan' => 'Banjarkerta', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110002', 'nama_kelurahan' => 'Karanganyar', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110003', 'nama_kelurahan' => 'Jambudesa', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110004', 'nama_kelurahan' => 'Kalijaran', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110005', 'nama_kelurahan' => 'Kaliori', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110006', 'nama_kelurahan' => 'Karanggedang', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110007', 'nama_kelurahan' => 'Karangkemiri', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110008', 'nama_kelurahan' => 'Karangtengah', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110009', 'nama_kelurahan' => 'Lumingser', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110010', 'nama_kelurahan' => 'Ponjen', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110011', 'nama_kelurahan' => 'Regunung', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110012', 'nama_kelurahan' => 'Sanguwatang', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],
            ['kode_kelurahan' => '3303110013', 'nama_kelurahan' => 'Bungkanel', 'id_kecamatan' => $kecamatanMap['PBG17'] ?? null, 'kode_pos' => '53354'],

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
