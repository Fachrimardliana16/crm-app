<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StatusPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_status_pengaduan')->insert([
            [
                'id_status_pengaduan' => Str::uuid(),
                'kode_status' => 'ST001',
                'nama_status' => 'Dilaporkan',
                'warna_tampilan' => 'gray',
                'status_aktif' => true,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_status_pengaduan' => Str::uuid(),
                'kode_status' => 'ST002',
                'nama_status' => 'Pengecekan',
                'warna_tampilan' => 'yellow',
                'status_aktif' => true,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_status_pengaduan' => Str::uuid(),
                'kode_status' => 'ST003',
                'nama_status' => 'Penanganan',
                'warna_tampilan' => 'blue',
                'status_aktif' => true,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_status_pengaduan' => Str::uuid(),
                'kode_status' => 'ST004',
                'nama_status' => 'Selesai',
                'warna_tampilan' => 'green',
                'status_aktif' => true,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_status_pengaduan' => Str::uuid(),
                'kode_status' => 'ST005',
                'nama_status' => 'Dibatalkan',
                'warna_tampilan' => 'red',
                'status_aktif' => true,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
        ]);
    }
}
