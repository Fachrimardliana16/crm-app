<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rayon;
use Illuminate\Support\Str;

class RayonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable activity logging temporarily for seeding
        activity()->disableLogging();
        
        $rayons = [
            [
                'kode_rayon' => '01',
                'nama_rayon' => 'Rayon Pusat Kota',
                'deskripsi' => 'Rayon yang melayani area pusat kota dan sekitarnya',
                'wilayah' => 'Kecamatan Klojen, Lowokwaru',
                'koordinat_pusat_lat' => -7.9666,
                'koordinat_pusat_lng' => 112.6326,
                'radius_coverage' => 5000,
                'kapasitas_maksimal' => 2000,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon utama yang melayani area komersial dan perkantoran'
            ],
            [
                'kode_rayon' => '02',
                'nama_rayon' => 'Rayon Selatan',
                'deskripsi' => 'Rayon yang melayani area selatan kota',
                'wilayah' => 'Kecamatan Blimbing, Sukun',
                'koordinat_pusat_lat' => -7.9797,
                'koordinat_pusat_lng' => 112.6304,
                'radius_coverage' => 6000,
                'kapasitas_maksimal' => 1500,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area perumahan dan industri kecil'
            ],
            [
                'kode_rayon' => '03',
                'nama_rayon' => 'Rayon Utara',
                'deskripsi' => 'Rayon yang melayani area utara kota',
                'wilayah' => 'Kecamatan Kedungkandang',
                'koordinat_pusat_lat' => -7.9553,
                'koordinat_pusat_lng' => 112.6281,
                'radius_coverage' => 7000,
                'kapasitas_maksimal' => 1800,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area perumahan dan pendidikan'
            ],
            [
                'kode_rayon' => '04',
                'nama_rayon' => 'Rayon Timur',
                'deskripsi' => 'Rayon yang melayani area timur kota',
                'wilayah' => 'Kecamatan Pakis, Tumpang',
                'koordinat_pusat_lat' => -7.9344,
                'koordinat_pusat_lng' => 112.6544,
                'radius_coverage' => 8000,
                'kapasitas_maksimal' => 1200,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area pegunungan dan wisata'
            ],
            [
                'kode_rayon' => '05',
                'nama_rayon' => 'Rayon Barat',
                'deskripsi' => 'Rayon yang melayani area barat kota',
                'wilayah' => 'Kecamatan Karangploso, Dau',
                'koordinat_pusat_lat' => -7.9389,
                'koordinat_pusat_lng' => 112.5906,
                'radius_coverage' => 9000,
                'kapasitas_maksimal' => 1000,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area pertanian dan perkebunan'
            ]
        ];

        foreach ($rayons as $rayonData) {
            Rayon::create([
                'id_rayon' => Str::uuid(),
                'kode_rayon' => $rayonData['kode_rayon'],
                'nama_rayon' => $rayonData['nama_rayon'],
                'deskripsi' => $rayonData['deskripsi'],
                'wilayah' => $rayonData['wilayah'],
                'koordinat_pusat_lat' => $rayonData['koordinat_pusat_lat'],
                'koordinat_pusat_lng' => $rayonData['koordinat_pusat_lng'],
                'radius_coverage' => $rayonData['radius_coverage'],
                'jumlah_pelanggan' => 0,
                'kapasitas_maksimal' => $rayonData['kapasitas_maksimal'],
                'status_aktif' => $rayonData['status_aktif'],
                'keterangan' => $rayonData['keterangan'],
                'dibuat_oleh' => 'System',
                'dibuat_pada' => now(),
            ]);
        }

        $this->command->info('✅ Berhasil membuat ' . count($rayons) . ' data Rayon');
        
        // Re-enable activity logging
        activity()->enableLogging();
    }
}
