<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rayon;
use App\Models\SubRayon;
use Illuminate\Support\Str;

class SubRayonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable activity logging temporarily for seeding
        activity()->disableLogging();
        
        // Data Sub Rayon untuk setiap Rayon
        $subRayonData = [
            '01' => [ // Rayon Pusat Kota
                [
                    'kode_sub_rayon' => '0001',
                    'nama_sub_rayon' => 'Sub Rayon Alun-Alun',
                    'deskripsi' => 'Melayani area sekitar alun-alun kota',
                    'wilayah' => 'Kelurahan Kauman, Kidul Dalem',
                    'koordinat_pusat_lat' => -7.9797,
                    'koordinat_pusat_lng' => 112.6304,
                    'radius_coverage' => 2000,
                    'kapasitas_maksimal' => 500,
                ],
                [
                    'kode_sub_rayon' => '0002',
                    'nama_sub_rayon' => 'Sub Rayon Rampal Celaket',
                    'deskripsi' => 'Melayani area Rampal Celaket',
                    'wilayah' => 'Kelurahan Rampal Celaket, Oro-oro Dowo',
                    'koordinat_pusat_lat' => -7.9756,
                    'koordinat_pusat_lng' => 112.6267,
                    'radius_coverage' => 1800,
                    'kapasitas_maksimal' => 400,
                ],
                [
                    'kode_sub_rayon' => '0003',
                    'nama_sub_rayon' => 'Sub Rayon Klojen',
                    'deskripsi' => 'Melayani area pusat bisnis Klojen',
                    'wilayah' => 'Kelurahan Klojen, Gading Kasri',
                    'koordinat_pusat_lat' => -7.9689,
                    'koordinat_pusat_lng' => 112.6353,
                    'radius_coverage' => 2200,
                    'kapasitas_maksimal' => 600,
                ],
            ],
            '02' => [ // Rayon Selatan
                [
                    'kode_sub_rayon' => '0004',
                    'nama_sub_rayon' => 'Sub Rayon Sukun',
                    'deskripsi' => 'Melayani area Sukun dan sekitarnya',
                    'wilayah' => 'Kelurahan Sukun, Bandulan',
                    'koordinat_pusat_lat' => -7.9944,
                    'koordinat_pusat_lng' => 112.6178,
                    'radius_coverage' => 2500,
                    'kapasitas_maksimal' => 450,
                ],
                [
                    'kode_sub_rayon' => '0005',
                    'nama_sub_rayon' => 'Sub Rayon Tanjungrejo',
                    'deskripsi' => 'Melayani area Tanjungrejo',
                    'wilayah' => 'Kelurahan Tanjungrejo, Mulyorejo',
                    'koordinat_pusat_lat' => -8.0022,
                    'koordinat_pusat_lng' => 112.6089,
                    'radius_coverage' => 2000,
                    'kapasitas_maksimal' => 350,
                ],
            ],
            '03' => [ // Rayon Utara
                [
                    'kode_sub_rayon' => '0006',
                    'nama_sub_rayon' => 'Sub Rayon Lowokwaru',
                    'deskripsi' => 'Melayani area Lowokwaru',
                    'wilayah' => 'Kelurahan Lowokwaru, Tulusrejo',
                    'koordinat_pusat_lat' => -7.9447,
                    'koordinat_pusat_lng' => 112.6244,
                    'radius_coverage' => 2800,
                    'kapasitas_maksimal' => 550,
                ],
                [
                    'kode_sub_rayon' => '0007',
                    'nama_sub_rayon' => 'Sub Rayon Mojolangu',
                    'deskripsi' => 'Melayani area Mojolangu',
                    'wilayah' => 'Kelurahan Mojolangu, Landungsari',
                    'koordinat_pusat_lat' => -7.9289,
                    'koordinat_pusat_lng' => 112.6178,
                    'radius_coverage' => 2400,
                    'kapasitas_maksimal' => 400,
                ],
            ],
            '04' => [ // Rayon Timur
                [
                    'kode_sub_rayon' => '0008',
                    'nama_sub_rayon' => 'Sub Rayon Dinoyo',
                    'deskripsi' => 'Melayani area Dinoyo',
                    'wilayah' => 'Kelurahan Dinoyo, Jatimulyo',
                    'koordinat_pusat_lat' => -7.9456,
                    'koordinat_pusat_lng' => 112.6422,
                    'radius_coverage' => 2600,
                    'kapasitas_maksimal' => 380,
                ],
                [
                    'kode_sub_rayon' => '0009',
                    'nama_sub_rayon' => 'Sub Rayon Kedungkandang',
                    'deskripsi' => 'Melayani area Kedungkandang',
                    'wilayah' => 'Kelurahan Kedungkandang, Sawojajar',
                    'koordinat_pusat_lat' => -7.9567,
                    'koordinat_pusat_lng' => 112.6511,
                    'radius_coverage' => 3000,
                    'kapasitas_maksimal' => 420,
                ],
            ],
            '05' => [ // Rayon Barat
                [
                    'kode_sub_rayon' => '0010',
                    'nama_sub_rayon' => 'Sub Rayon Karangploso',
                    'deskripsi' => 'Melayani area Karangploso',
                    'wilayah' => 'Desa Bocek, Donowarih',
                    'koordinat_pusat_lat' => -7.9167,
                    'koordinat_pusat_lng' => 112.5733,
                    'radius_coverage' => 3500,
                    'kapasitas_maksimal' => 300,
                ],
            ],
        ];

        $totalSubRayon = 0;

        foreach ($subRayonData as $kodeRayon => $subRayons) {
            // Cari Rayon berdasarkan kode
            $rayon = Rayon::where('kode_rayon', $kodeRayon)->first();
            
            if (!$rayon) {
                $this->command->warn("⚠️ Rayon dengan kode {$kodeRayon} tidak ditemukan. Melewati...");
                continue;
            }

            foreach ($subRayons as $subRayonData) {
                SubRayon::create([
                    'id_sub_rayon' => Str::uuid(),
                    'id_rayon' => $rayon->id_rayon,
                    'kode_sub_rayon' => $subRayonData['kode_sub_rayon'],
                    'nama_sub_rayon' => $subRayonData['nama_sub_rayon'],
                    'deskripsi' => $subRayonData['deskripsi'],
                    'wilayah' => $subRayonData['wilayah'],
                    'koordinat_pusat_lat' => $subRayonData['koordinat_pusat_lat'],
                    'koordinat_pusat_lng' => $subRayonData['koordinat_pusat_lng'],
                    'radius_coverage' => $subRayonData['radius_coverage'],
                    'jumlah_pelanggan' => 0,
                    'kapasitas_maksimal' => $subRayonData['kapasitas_maksimal'],
                    'nomor_pelanggan_terakhir' => 0,
                    'status_aktif' => 'aktif',
                    'keterangan' => "Sub rayon untuk {$rayon->nama_rayon}",
                    'dibuat_oleh' => 'System',
                    'dibuat_pada' => now(),
                ]);
                
                $totalSubRayon++;
            }

            $this->command->info("✅ Berhasil membuat " . count($subRayons) . " Sub Rayon untuk {$rayon->nama_rayon}");
        }

        $this->command->info("🎉 Total berhasil membuat {$totalSubRayon} data Sub Rayon");
        
        // Re-enable activity logging
        activity()->enableLogging();
    }
}
