<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rayon;
use App\Models\SubRayon;
use Illuminate\Support\Str;

class RayonSubRayonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Rayon yang akan dibuat
        $rayonData = [
            [
                'kode_rayon' => '01',
                'nama_rayon' => 'Rayon Pusat',
                'deskripsi' => 'Rayon yang melayani area pusat kota',
                'wilayah' => 'Kecamatan Pusat, Kecamatan Tengah',
                'koordinat_pusat_lat' => -7.2574719,
                'koordinat_pusat_lng' => 112.7520883,
                'radius_coverage' => 5000,
                'kapasitas_maksimal' => 2000,
                'sub_rayons' => [
                    [
                        'kode_sub_rayon' => '0001',
                        'nama_sub_rayon' => 'Sub Rayon Pusat A',
                        'deskripsi' => 'Melayani area pusat kota bagian utara',
                        'wilayah' => 'RT 01-10, RW 01-05',
                        'kapasitas_maksimal' => 500,
                    ],
                    [
                        'kode_sub_rayon' => '0002',
                        'nama_sub_rayon' => 'Sub Rayon Pusat B',
                        'deskripsi' => 'Melayani area pusat kota bagian selatan',
                        'wilayah' => 'RT 11-20, RW 06-10',
                        'kapasitas_maksimal' => 500,
                    ],
                    [
                        'kode_sub_rayon' => '0003',
                        'nama_sub_rayon' => 'Sub Rayon Pusat C',
                        'deskripsi' => 'Melayani area pusat kota bagian timur',
                        'wilayah' => 'RT 21-30, RW 11-15',
                        'kapasitas_maksimal' => 500,
                    ],
                    [
                        'kode_sub_rayon' => '0004',
                        'nama_sub_rayon' => 'Sub Rayon Pusat D',
                        'deskripsi' => 'Melayani area pusat kota bagian barat',
                        'wilayah' => 'RT 31-40, RW 16-20',
                        'kapasitas_maksimal' => 500,
                    ],
                ]
            ],
            [
                'kode_rayon' => '02',
                'nama_rayon' => 'Rayon Utara',
                'deskripsi' => 'Rayon yang melayani area utara kota',
                'wilayah' => 'Kecamatan Utara, Kecamatan Timur Laut',
                'koordinat_pusat_lat' => -7.2374719,
                'koordinat_pusat_lng' => 112.7420883,
                'radius_coverage' => 7000,
                'kapasitas_maksimal' => 1500,
                'sub_rayons' => [
                    [
                        'kode_sub_rayon' => '0001',
                        'nama_sub_rayon' => 'Sub Rayon Utara A',
                        'deskripsi' => 'Melayani area utara kota bagian timur',
                        'wilayah' => 'RT 01-15, RW 01-08',
                        'kapasitas_maksimal' => 500,
                    ],
                    [
                        'kode_sub_rayon' => '0002',
                        'nama_sub_rayon' => 'Sub Rayon Utara B',
                        'deskripsi' => 'Melayani area utara kota bagian barat',
                        'wilayah' => 'RT 16-30, RW 09-16',
                        'kapasitas_maksimal' => 500,
                    ],
                    [
                        'kode_sub_rayon' => '0003',
                        'nama_sub_rayon' => 'Sub Rayon Utara C',
                        'deskripsi' => 'Melayani area utara kota bagian tengah',
                        'wilayah' => 'RT 31-45, RW 17-24',
                        'kapasitas_maksimal' => 500,
                    ],
                ]
            ],
            [
                'kode_rayon' => '03',
                'nama_rayon' => 'Rayon Selatan',
                'deskripsi' => 'Rayon yang melayani area selatan kota',
                'wilayah' => 'Kecamatan Selatan, Kecamatan Barat Daya',
                'koordinat_pusat_lat' => -7.2774719,
                'koordinat_pusat_lng' => 112.7620883,
                'radius_coverage' => 6000,
                'kapasitas_maksimal' => 1800,
                'sub_rayons' => [
                    [
                        'kode_sub_rayon' => '0001',
                        'nama_sub_rayon' => 'Sub Rayon Selatan A',
                        'deskripsi' => 'Melayani area selatan kota bagian timur',
                        'wilayah' => 'RT 01-12, RW 01-06',
                        'kapasitas_maksimal' => 600,
                    ],
                    [
                        'kode_sub_rayon' => '0002',
                        'nama_sub_rayon' => 'Sub Rayon Selatan B',
                        'deskripsi' => 'Melayani area selatan kota bagian barat',
                        'wilayah' => 'RT 13-24, RW 07-12',
                        'kapasitas_maksimal' => 600,
                    ],
                    [
                        'kode_sub_rayon' => '0003',
                        'nama_sub_rayon' => 'Sub Rayon Selatan C',
                        'deskripsi' => 'Melayani area selatan kota bagian tengah',
                        'wilayah' => 'RT 25-36, RW 13-18',
                        'kapasitas_maksimal' => 600,
                    ],
                ]
            ],
        ];

        $this->command->info('Mulai seeding data Rayon dan Sub Rayon...');

        foreach ($rayonData as $rayonInfo) {
            $this->command->info("Membuat Rayon: {$rayonInfo['nama_rayon']}");
            
            // Buat data Rayon
            $subRayons = $rayonInfo['sub_rayons'];
            unset($rayonInfo['sub_rayons']);
            
            $rayon = Rayon::create([
                'id_rayon' => Str::uuid(),
                ...$rayonInfo,
                'status_aktif' => 'aktif',
                'jumlah_pelanggan' => 0,
                'dibuat_oleh' => 'System Seeder',
                'dibuat_pada' => now(),
            ]);

            $this->command->info("✓ Rayon {$rayon->nama_rayon} berhasil dibuat");

            // Buat Sub Rayon untuk rayon ini
            foreach ($subRayons as $subRayonInfo) {
                $this->command->info("  Membuat Sub Rayon: {$subRayonInfo['nama_sub_rayon']}");
                
                $subRayon = SubRayon::create([
                    'id_sub_rayon' => Str::uuid(),
                    'id_rayon' => $rayon->id_rayon,
                    ...$subRayonInfo,
                    'status_aktif' => 'aktif',
                    'jumlah_pelanggan' => 0,
                    'nomor_pelanggan_terakhir' => 0,
                    'dibuat_oleh' => 'System Seeder',
                    'dibuat_pada' => now(),
                ]);

                $this->command->info("  ✓ Sub Rayon {$subRayon->nama_sub_rayon} berhasil dibuat");
            }

            $this->command->info("");
        }

        $this->command->info('=== SEEDING COMPLETED ===');
        $this->command->info('Total Rayon: ' . Rayon::count());
        $this->command->info('Total Sub Rayon: ' . SubRayon::count());
        
        $this->command->info("\n📋 DAFTAR KODE YANG DIBUAT:");
        
        foreach (Rayon::with('subRayons')->get() as $rayon) {
            $this->command->info("Rayon {$rayon->kode_rayon}: {$rayon->nama_rayon}");
            foreach ($rayon->subRayons as $subRayon) {
                $kodeGabungan = $rayon->kode_rayon . substr($subRayon->kode_sub_rayon, -2);
                $this->command->info("  └─ Sub Rayon {$subRayon->kode_sub_rayon} → Kode Gabungan: {$kodeGabungan}");
            }
        }

        $this->command->info("\n🔢 CONTOH NOMOR PELANGGAN:");
        $this->command->info("Format: [Kode Rayon][2 Digit Terakhir Sub Rayon][Nomor Urut]");
        $this->command->info("Contoh: 01010001, 01020001, 02010001, 03030001");
    }
}
