<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = [
            ['kode' => 'DIRUT', 'nama_jabatan' => 'Direktur Utama'],
            ['kode' => 'DIRUM', 'nama_jabatan' => 'Direktur Umum'],
            ['kode' => 'KABAG', 'nama_jabatan' => 'Kepala Bagian'],
            ['kode' => 'KASUB', 'nama_jabatan' => 'Kepala Sub Bagian'],
            ['kode' => 'STAFF', 'nama_jabatan' => 'Staff'],
        ];

        foreach ($jabatan as $item) {
            Jabatan::updateOrCreate(
                ['kode' => $item['kode']],
                ['id' => (string) Str::uuid(), 'nama_jabatan' => $item['nama_jabatan']]
            );
        }
    }
}
