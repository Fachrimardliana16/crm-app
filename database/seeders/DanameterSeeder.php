<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Danameter;
use Illuminate\Support\Str;

class DanameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        Danameter::query()->delete();

        $danameterData = [
            [
                'kode_danameter' => 'D05',
                'diameter_pipa' => 'Diameter 1/2"',
                'tarif_danameter' => 3000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 1/2 inch (12.7 mm)',
                'urutan' => 1,
            ],
            [
                'kode_danameter' => 'D75',
                'diameter_pipa' => 'Diameter 3/4"',
                'tarif_danameter' => 7000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 3/4 inch (19.1 mm)',
                'urutan' => 2,
            ],
            [
                'kode_danameter' => 'D10',
                'diameter_pipa' => 'Diameter 1"',
                'tarif_danameter' => 9500,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 1 inch (25.4 mm)',
                'urutan' => 3,
            ],
            [
                'kode_danameter' => 'D15',
                'diameter_pipa' => 'Diameter 1 1/2"',
                'tarif_danameter' => 12000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 1 1/2 inch (38.1 mm)',
                'urutan' => 4,
            ],
            [
                'kode_danameter' => 'D20',
                'diameter_pipa' => 'Diameter 2"',
                'tarif_danameter' => 17000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 2 inch (50.8 mm)',
                'urutan' => 5,
            ],
            [
                'kode_danameter' => 'D30',
                'diameter_pipa' => 'Diameter 3"',
                'tarif_danameter' => 22000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 3 inch (76.2 mm)',
                'urutan' => 6,
            ],
            [
                'kode_danameter' => 'D40',
                'diameter_pipa' => 'Diameter 4"',
                'tarif_danameter' => 42000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 4 inch (101.6 mm)',
                'urutan' => 7,
            ],
            [
                'kode_danameter' => 'D60',
                'diameter_pipa' => 'Diameter 6"',
                'tarif_danameter' => 52000,
                'deskripsi' => 'Tarif danameter untuk diameter pipa 6 inch (152.4 mm)',
                'urutan' => 8,
            ],
        ];

        foreach ($danameterData as $data) {
            Danameter::create([
                'id_danameter' => (string) Str::uuid(),
                'kode_danameter' => $data['kode_danameter'],
                'diameter_pipa' => $data['diameter_pipa'],
                'tarif_danameter' => $data['tarif_danameter'],
                'deskripsi' => $data['deskripsi'],
                'is_active' => true,
                'urutan' => $data['urutan'],
            ]);
        }

        $this->command->info('✅ Berhasil membuat ' . count($danameterData) . ' data Danameter');
    }
}
