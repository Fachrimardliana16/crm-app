<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pajakData = [
            [
                'kode_pajak' => 'PPN',
                'nama_pajak' => 'Pajak Pertambahan Nilai',
                'deskripsi' => 'Pajak Pertambahan Nilai (PPN) 11% sesuai regulasi terbaru',
                'persentase_pajak' => 11.00,
                'nilai_tetap' => null,
                'jenis_pajak' => 'persentase',
                'status_aktif' => true,
            ],
        ];

        foreach ($pajakData as $data) {
            \App\Models\Pajak::updateOrCreate(
                ['kode_pajak' => $data['kode_pajak']],
                $data
            );
        }
    }
}
