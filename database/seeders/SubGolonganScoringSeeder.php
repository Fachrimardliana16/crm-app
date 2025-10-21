<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubGolonganPelanggan;

class SubGolonganScoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update scoring data untuk setiap sub golongan
        $scoringData = [
            // SOSIAL
            'SOC-KH' => [
                'skor_minimum' => 0,
                'skor_maksimum' => 40,
                'kriteria_scoring' => 'Rumah ibadah, panti asuhan, yayasan sosial dengan kondisi bangunan sederhana',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 100,
            ],
            'SOC-HU' => [
                'skor_minimum' => 0,
                'skor_maksimum' => 35,
                'kriteria_scoring' => 'Sekolah, puskesmas, fasilitas umum dengan kondisi bangunan standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 95,
            ],

            // RUMAH TANGGA
            'RT-A' => [
                'skor_minimum' => 20,
                'skor_maksimum' => 50,
                'kriteria_scoring' => 'Rumah sederhana dengan luas tanah kecil, material sederhana, tanpa kendaraan bermotor',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 90,
            ],
            'RT-B' => [
                'skor_minimum' => 51,
                'skor_maksimum' => 80,
                'kriteria_scoring' => 'Rumah menengah dengan luas tanah sedang, material semi permanen, memiliki sepeda motor',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 85,
            ],
            'RT-C' => [
                'skor_minimum' => 81,
                'skor_maksimum' => 120,
                'kriteria_scoring' => 'Rumah menengah ke atas dengan luas tanah cukup, material permanen, memiliki mobil',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 80,
            ],
            'RT-KH' => [
                'skor_minimum' => 121,
                'skor_maksimum' => null,
                'kriteria_scoring' => 'Rumah mewah dengan luas tanah besar, material premium, kendaraan mewah',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 75,
            ],

            // INSTANSI
            'INS-PEM' => [
                'skor_minimum' => 60,
                'skor_maksimum' => null,
                'kriteria_scoring' => 'Kantor instansi pemerintah dengan bangunan permanen dan fasilitas lengkap',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 70,
            ],

            // TNI/POLRI
            'TNI' => [
                'skor_minimum' => 30,
                'skor_maksimum' => 70,
                'kriteria_scoring' => 'Mess/asrama TNI dengan fasilitas standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 65,
            ],
            'POLRI' => [
                'skor_minimum' => 30,
                'skor_maksimum' => 70,
                'kriteria_scoring' => 'Mess/asrama Polri dengan fasilitas standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 65,
            ],

            // NIAGA
            'NGA-KC' => [
                'skor_minimum' => 40,
                'skor_maksimum' => 90,
                'kriteria_scoring' => 'Usaha kecil-menengah dengan bangunan sederhana hingga semi permanen',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 60,
            ],
            'NGA-BS' => [
                'skor_minimum' => 91,
                'skor_maksimum' => null,
                'kriteria_scoring' => 'Usaha besar dengan bangunan permanen dan fasilitas lengkap',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 55,
            ],

            // INDUSTRI
            'IND-KC' => [
                'skor_minimum' => 60,
                'skor_maksimum' => 110,
                'kriteria_scoring' => 'Industri kecil-menengah dengan bangunan standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 50,
            ],
            'IND-BS' => [
                'skor_minimum' => 111,
                'skor_maksimum' => null,
                'kriteria_scoring' => 'Industri besar dengan bangunan dan fasilitas lengkap',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 45,
            ],
        ];

        foreach ($scoringData as $kodeSubGolongan => $data) {
            $subGolongan = SubGolonganPelanggan::where('kode_sub_golongan', $kodeSubGolongan)->first();
            
            if ($subGolongan) {
                $subGolongan->update($data);
                $this->command->info("Updated scoring data for: {$kodeSubGolongan}");
            } else {
                $this->command->warn("Sub golongan not found: {$kodeSubGolongan}");
            }
        }

        $this->command->info('Scoring data seeder completed successfully!');
    }
}