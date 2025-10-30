<?php

namespace Database\Seeders;

use App\Models\Bagian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BagianSeeder extends Seeder
{
    public function run(): void
    {
        $bagian = [
            ['kode' => 'KEU', 'nama_bagian' => 'Keuangan'],
            ['kode' => 'UMUM', 'nama_bagian' => 'Umum'],
            ['kode' => 'HL', 'nama_bagian' => 'Hubungan Langganan'],
            ['kode' => 'TEK', 'nama_bagian' => 'Teknik'],
            ['kode' => 'SPI', 'nama_bagian' => 'SPI'],
        ];

        foreach ($bagian as $item) {
            Bagian::updateOrCreate(
                ['kode' => $item['kode']],
                ['id' => (string) Str::uuid(), 'nama_bagian' => $item['nama_bagian']]
            );
        }
    }
}
