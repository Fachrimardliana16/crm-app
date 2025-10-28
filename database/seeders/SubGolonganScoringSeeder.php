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
        // Total skor maksimum = 11 parameter × 10 skor = 110 poin
        // Total skor minimum = 11 parameter × 2 skor = 22 poin
        // SISTEM ANTI-OVERLAP: Gunakan prioritas untuk menangani overlap yang memang diperlukan
        $scoringData = [
            // SOSIAL - Range 22-35 (Prioritas tertinggi untuk sosial)
            'SOC-KH' => [
                'skor_minimum' => 22,
                'skor_maksimum' => 30,
                'kriteria_scoring' => 'Rumah ibadah, panti asuhan, yayasan sosial dengan kondisi bangunan sederhana',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 100,
            ],
            'SOC-HU' => [
                'skor_minimum' => 31,
                'skor_maksimum' => 35,
                'kriteria_scoring' => 'Sekolah, puskesmas, fasilitas umum dengan kondisi bangunan standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 99,
            ],

            // RUMAH TANGGA - Range 36-75 (Gradual sesuai kemampuan)
            'RT-A' => [
                'skor_minimum' => 36,
                'skor_maksimum' => 50,
                'kriteria_scoring' => 'Rumah sederhana dengan luas tanah kecil, material sederhana, tanpa kendaraan bermotor',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 90,
            ],
            'RT-B' => [
                'skor_minimum' => 51,
                'skor_maksimum' => 65,
                'kriteria_scoring' => 'Rumah menengah dengan luas tanah sedang, material semi permanen, memiliki sepeda motor',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 85,
            ],
            'RT-C' => [
                'skor_minimum' => 66,
                'skor_maksimum' => 75,
                'kriteria_scoring' => 'Rumah menengah ke atas dengan luas tanah cukup, material permanen, memiliki mobil',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 80,
            ],

            // NIAGA - Range 76-90 (Sesuai skala usaha) 
            'NGA-KC' => [
                'skor_minimum' => 76,
                'skor_maksimum' => 83,
                'kriteria_scoring' => 'Usaha kecil-menengah dengan bangunan sederhana hingga semi permanen',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 60,
            ],
            'NGA-BS' => [
                'skor_minimum' => 84,
                'skor_maksimum' => 90,
                'kriteria_scoring' => 'Usaha besar dengan bangunan permanen dan fasilitas lengkap',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 55,
            ],

            // INDUSTRI - Range 91-100 (Sesuai skala industri)
            'IND-KC' => [
                'skor_minimum' => 91,
                'skor_maksimum' => 95,
                'kriteria_scoring' => 'Industri kecil-menengah dengan bangunan standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 50,
            ],
            'IND-BS' => [
                'skor_minimum' => 96,
                'skor_maksimum' => 100,
                'kriteria_scoring' => 'Industri besar dengan bangunan dan fasilitas lengkap',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 45,
            ],

            // RUMAH TANGGA KHUSUS - Range tertinggi
            'RT-KH' => [
                'skor_minimum' => 101,
                'skor_maksimum' => 110,
                'kriteria_scoring' => 'Rumah mewah dengan luas tanah besar, material premium, kendaraan mewah',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 75,
            ],

            // INSTANSI/TNI/POLRI - Overlap dengan RT-B/RT-C tapi prioritas lebih tinggi
            'TNI' => [
                'skor_minimum' => 51,
                'skor_maksimum' => 75,
                'kriteria_scoring' => 'Mess/asrama TNI dengan fasilitas standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 88, // Lebih tinggi dari RT-B/RT-C
            ],
            'POLRI' => [
                'skor_minimum' => 51,
                'skor_maksimum' => 75,
                'kriteria_scoring' => 'Mess/asrama Polri dengan fasilitas standar',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 87, // Lebih tinggi dari RT-B/RT-C
            ],
            'INS-PEM' => [
                'skor_minimum' => 66,
                'skor_maksimum' => 83,
                'kriteria_scoring' => 'Kantor instansi pemerintah dengan bangunan permanen dan fasilitas lengkap',
                'gunakan_scoring' => true,
                'prioritas_scoring' => 82, // Lebih tinggi dari RT-C, tapi lebih rendah dari TNI/POLRI
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