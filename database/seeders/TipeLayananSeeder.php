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
                'biaya_standar' => 500000,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_layanan' => 'SP',
                'nama_tipe_layanan' => 'Sambungan Pindah',
                'deskripsi' => 'Pemindahan sambungan air dari lokasi lama ke lokasi baru',
                'biaya_standar' => 300000,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_layanan' => 'PK',
                'nama_tipe_layanan' => 'Peningkatan Kapasitas',
                'deskripsi' => 'Peningkatan kapasitas meter atau sambungan untuk kebutuhan air yang lebih besar',
                'biaya_standar' => 250000,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_layanan' => 'RA',
                'nama_tipe_layanan' => 'Reaktivasi',
                'deskripsi' => 'Reaktivasi sambungan yang sudah non-aktif atau putus',
                'biaya_standar' => 150000,
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
