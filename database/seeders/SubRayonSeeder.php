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
            '01' => [ // Rayon Purbalingga Kota
                [
                    'kode_sub_rayon' => '0001',
                    'nama_sub_rayon' => 'Sub Rayon Purbalingga Kulon',
                    'deskripsi' => 'Melayani area Purbalingga bagian barat',
                    'wilayah' => 'Desa Purbalingga Kulon, Purbalingga Lor',
                    'koordinat_pusat_lat' => -7.3850,
                    'koordinat_pusat_lng' => 109.3600,
                    'radius_coverage' => 2000,
                    'kapasitas_maksimal' => 600,
                ],
                [
                    'kode_sub_rayon' => '0002',
                    'nama_sub_rayon' => 'Sub Rayon Purbalingga Wetan',
                    'deskripsi' => 'Melayani area Purbalingga bagian timur',
                    'wilayah' => 'Desa Purbalingga Wetan, Krangean',
                    'koordinat_pusat_lat' => -7.3900,
                    'koordinat_pusat_lng' => 109.3700,
                    'radius_coverage' => 1800,
                    'kapasitas_maksimal' => 500,
                ],
                [
                    'kode_sub_rayon' => '0003',
                    'nama_sub_rayon' => 'Sub Rayon Kalimanah',
                    'deskripsi' => 'Melayani area Kalimanah dan sekitarnya',
                    'wilayah' => 'Desa Kalimanah Kulon, Kalimanah Wetan',
                    'koordinat_pusat_lat' => -7.3950,
                    'koordinat_pusat_lng' => 109.3750,
                    'radius_coverage' => 2200,
                    'kapasitas_maksimal' => 700,
                ],
            ],
            '02' => [ // Rayon Utara
                [
                    'kode_sub_rayon' => '0004',
                    'nama_sub_rayon' => 'Sub Rayon Kemangkon',
                    'deskripsi' => 'Melayani area Kemangkon dan sekitarnya',
                    'wilayah' => 'Desa Kemangkon, Windujaya',
                    'koordinat_pusat_lat' => -7.3100,
                    'koordinat_pusat_lng' => 109.3400,
                    'radius_coverage' => 2500,
                    'kapasitas_maksimal' => 450,
                ],
                [
                    'kode_sub_rayon' => '0005',
                    'nama_sub_rayon' => 'Sub Rayon Bukateja',
                    'deskripsi' => 'Melayani area Bukateja',
                    'wilayah' => 'Desa Bukateja, Sokawera',
                    'koordinat_pusat_lat' => -7.3200,
                    'koordinat_pusat_lng' => 109.3300,
                    'radius_coverage' => 3000,
                    'kapasitas_maksimal' => 400,
                ],
                [
                    'kode_sub_rayon' => '0006',
                    'nama_sub_rayon' => 'Sub Rayon Karangmoncol',
                    'deskripsi' => 'Melayani area Karangmoncol',
                    'wilayah' => 'Desa Karangmoncol, Panembahan',
                    'koordinat_pusat_lat' => -7.3000,
                    'koordinat_pusat_lng' => 109.3600,
                    'radius_coverage' => 2800,
                    'kapasitas_maksimal' => 350,
                ],
            ],
            '03' => [ // Rayon Selatan
                [
                    'kode_sub_rayon' => '0007',
                    'nama_sub_rayon' => 'Sub Rayon Kutasari',
                    'deskripsi' => 'Melayani area Kutasari',
                    'wilayah' => 'Desa Kutasari, Sangkanayu',
                    'koordinat_pusat_lat' => -7.4400,
                    'koordinat_pusat_lng' => 109.3700,
                    'radius_coverage' => 2600,
                    'kapasitas_maksimal' => 500,
                ],
                [
                    'kode_sub_rayon' => '0008',
                    'nama_sub_rayon' => 'Sub Rayon Mrebet',
                    'deskripsi' => 'Melayani area Mrebet',
                    'wilayah' => 'Desa Mrebet, Panusupan',
                    'koordinat_pusat_lat' => -7.4600,
                    'koordinat_pusat_lng' => 109.3900,
                    'radius_coverage' => 3200,
                    'kapasitas_maksimal' => 450,
                ],
            ],
            '04' => [ // Rayon Timur
                [
                    'kode_sub_rayon' => '0009',
                    'nama_sub_rayon' => 'Sub Rayon Bobotsari',
                    'deskripsi' => 'Melayani area Bobotsari',
                    'wilayah' => 'Desa Bobotsari, Pageralang',
                    'koordinat_pusat_lat' => -7.3600,
                    'koordinat_pusat_lng' => 109.4100,
                    'radius_coverage' => 2400,
                    'kapasitas_maksimal' => 400,
                ],
                [
                    'kode_sub_rayon' => '0010',
                    'nama_sub_rayon' => 'Sub Rayon Karangreja',
                    'deskripsi' => 'Melayani area Karangreja',
                    'wilayah' => 'Desa Karangreja, Penambangan',
                    'koordinat_pusat_lat' => -7.3800,
                    'koordinat_pusat_lng' => 109.4300,
                    'radius_coverage' => 2700,
                    'kapasitas_maksimal' => 380,
                ],
            ],
            '05' => [ // Rayon Barat
                [
                    'kode_sub_rayon' => '0011',
                    'nama_sub_rayon' => 'Sub Rayon Pengadegan',
                    'deskripsi' => 'Melayani area Pengadegan',
                    'wilayah' => 'Desa Pengadegan, Sidamulya',
                    'koordinat_pusat_lat' => -7.4100,
                    'koordinat_pusat_lng' => 109.2900,
                    'radius_coverage' => 3500,
                    'kapasitas_maksimal' => 300,
                ],
                [
                    'kode_sub_rayon' => '0012',
                    'nama_sub_rayon' => 'Sub Rayon Rembang',
                    'deskripsi' => 'Melayani area Rembang',
                    'wilayah' => 'Desa Rembang, Karangsalam',
                    'koordinat_pusat_lat' => -7.3900,
                    'koordinat_pusat_lng' => 109.3100,
                    'radius_coverage' => 4000,
                    'kapasitas_maksimal' => 250,
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
