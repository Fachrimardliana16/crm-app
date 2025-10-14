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
                'nama_rayon' => 'Rayon Purbalingga Kota',
                'deskripsi' => 'Rayon yang melayani area pusat kota Purbalingga',
                'wilayah' => 'Kecamatan Purbalingga, Kalimanah',
                'koordinat_pusat_lat' => -7.3881,
                'koordinat_pusat_lng' => 109.3668,
                'radius_coverage' => 5000,
                'kapasitas_maksimal' => 2000,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon utama yang melayani area pusat kota dan perkantoran'
            ],
            [
                'kode_rayon' => '02',
                'nama_rayon' => 'Rayon Utara',
                'deskripsi' => 'Rayon yang melayani area utara Purbalingga',
                'wilayah' => 'Kecamatan Kemangkon, Bukateja',
                'koordinat_pusat_lat' => -7.3200,
                'koordinat_pusat_lng' => 109.3500,
                'radius_coverage' => 7000,
                'kapasitas_maksimal' => 1500,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area perkebunan dan pertanian utara'
            ],
            [
                'kode_rayon' => '03',
                'nama_rayon' => 'Rayon Selatan',
                'deskripsi' => 'Rayon yang melayani area selatan Purbalingga',
                'wilayah' => 'Kecamatan Kutasari, Mrebet',
                'koordinat_pusat_lat' => -7.4500,
                'koordinat_pusat_lng' => 109.3800,
                'radius_coverage' => 8000,
                'kapasitas_maksimal' => 1800,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area peternakan dan perkebunan'
            ],
            [
                'kode_rayon' => '04',
                'nama_rayon' => 'Rayon Timur',
                'deskripsi' => 'Rayon yang melayani area timur Purbalingga',
                'wilayah' => 'Kecamatan Bobotsari, Karangreja',
                'koordinat_pusat_lat' => -7.3700,
                'koordinat_pusat_lng' => 109.4200,
                'radius_coverage' => 6000,
                'kapasitas_maksimal' => 1200,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area industri dan perdagangan timur'
            ],
            [
                'kode_rayon' => '05',
                'nama_rayon' => 'Rayon Barat',
                'deskripsi' => 'Rayon yang melayani area barat Purbalingga',
                'wilayah' => 'Kecamatan Pengadegan, Rembang',
                'koordinat_pusat_lat' => -7.4000,
                'koordinat_pusat_lng' => 109.3000,
                'radius_coverage' => 9000,
                'kapasitas_maksimal' => 1000,
                'status_aktif' => 'aktif',
                'keterangan' => 'Rayon yang melayani area pegunungan dan wisata alam'
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
