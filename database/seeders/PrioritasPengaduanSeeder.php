<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrioritasPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_prioritas_pengaduan')->insert([
            [
                'id_prioritas_pengaduan' => Str::uuid(),
                'kode_prioritas' => 'PR001',
                'nama_prioritas' => 'Rendah',
                'sla_jam' => 72,
                'warna_tampilan' => 'green',
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_prioritas_pengaduan' => Str::uuid(),
                'kode_prioritas' => 'PR002',
                'nama_prioritas' => 'Sedang',
                'sla_jam' => 48,
                'warna_tampilan' => 'yellow',
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_prioritas_pengaduan' => Str::uuid(),
                'kode_prioritas' => 'PR003',
                'nama_prioritas' => 'Tinggi',
                'sla_jam' => 24,
                'warna_tampilan' => 'orange',
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_prioritas_pengaduan' => Str::uuid(),
                'kode_prioritas' => 'PR004',
                'nama_prioritas' => 'Darurat',
                'sla_jam' => 6,
                'warna_tampilan' => 'red',
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
        ]);
    }
}
