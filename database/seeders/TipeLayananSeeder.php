<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipeLayanan;

class TipeLayananSeeder extends Seeder
{
    public function run(): void
    {
        $tipeLayananData = [
            [
                'kode_tipe_layanan' => 'SB',
                'nama_tipe_layanan' => 'Sambungan Baru',
                'deskripsi' => 'Pemasangan sambungan air bersih baru untuk pelanggan yang belum pernah memiliki sambungan',
                'biaya_standar' => 100000,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_layanan' => 'GM',
                'nama_tipe_layanan' => 'Ganti Meter',
                'deskripsi' => 'Penggantian meter air yang rusak atau expired',
                'biaya_standar' => 200000,
                'status_aktif' => true,
            ],
        ];

        foreach ($tipeLayananData as $data) {
            TipeLayanan::updateOrCreate(
                ['kode_tipe_layanan' => $data['kode_tipe_layanan']],
                $data
            );
        }
    }
}
