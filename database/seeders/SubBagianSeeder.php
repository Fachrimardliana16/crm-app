<?php

namespace Database\Seeders;

use App\Models\Bagian;
use App\Models\SubBagian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubBagianSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Keuangan' => ['Sub Anggaran Pendapatan', 'Sub Verifikasi Pembukuan', 'Gudang'],
            'Umum' => ['Sub IT', 'Sub Kerumahtanggaan', 'Sub Kesekretariatan', 'Sub Hukum dan Humas'],
            'Hubungan Langganan' => ['Sub Layanan Pelanggan', 'Baca Meter', 'Pemasaran'],
            'Teknik' => ['Sub Perencanaan', 'Sub Produksi', 'Sub NRW GIS', 'Sub Transmisi dan Distribusi'],
            'SPI' => []
        ];

        foreach ($data as $namaBagian => $subs) {
            $bagian = Bagian::where('nama_bagian', $namaBagian)->first();
            if (!$bagian) continue;

            foreach ($subs as $namaSub) {
                SubBagian::updateOrCreate(
                    ['bagian_id' => $bagian->id, 'nama_sub_bagian' => $namaSub],
                    ['id' => (string) Str::uuid()]
                );
            }
        }
    }
}
