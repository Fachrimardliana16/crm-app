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
            [
                'kode_pajak' => 'PPH21',
                'nama_pajak' => 'Pajak Penghasilan Pasal 21',
                'deskripsi' => 'Pajak Penghasilan Pasal 21 untuk pegawai',
                'persentase_pajak' => 5.00,
                'nilai_tetap' => null,
                'jenis_pajak' => 'persentase',
                'status_aktif' => true,
            ],
            [
                'kode_pajak' => 'PPH23',
                'nama_pajak' => 'Pajak Penghasilan Pasal 23',
                'deskripsi' => 'Pajak Penghasilan Pasal 23 untuk jasa',
                'persentase_pajak' => 2.00,
                'nilai_tetap' => null,
                'jenis_pajak' => 'persentase',
                'status_aktif' => true,
            ],
            [
                'kode_pajak' => 'ADM',
                'nama_pajak' => 'Biaya Administrasi',
                'deskripsi' => 'Biaya administrasi tetap untuk setiap transaksi',
                'persentase_pajak' => 0,
                'nilai_tetap' => 10000,
                'jenis_pajak' => 'nilai_tetap',
                'status_aktif' => true,
            ],
            [
                'kode_pajak' => 'MATERAI',
                'nama_pajak' => 'Meterai',
                'deskripsi' => 'Biaya meterai untuk dokumen resmi',
                'persentase_pajak' => 0,
                'nilai_tetap' => 10000,
                'jenis_pajak' => 'nilai_tetap',
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
