<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JenisPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jenis_pengaduan')->insert([
            [
                'id_jenis_pengaduan' => Str::uuid(),
                'kode_jenis' => 'JD001',
                'nama_jenis' => 'Aliran Pelanggan',
                'deskripsi' => 'Laporan mengenai aliran pelanggan.',
                'id_prioritas_pengaduan' => null,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_jenis_pengaduan' => Str::uuid(),
                'kode_jenis' => 'JD002',
                'nama_jenis' => 'Aliran Komplek',
                'deskripsi' => 'Laporan mengenai air tidak mengalir ke satu wilayah/komplek.',
                'id_prioritas_pengaduan' => null,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_jenis_pengaduan' => Str::uuid(),
                'kode_jenis' => 'JD003',
                'nama_jenis' => 'Bocor Pelanggan',
                'deskripsi' => 'Laporan tentang air yang bocor di area pelanggan.',
                'id_prioritas_pengaduan' => null,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_jenis_pengaduan' => Str::uuid(),
                'kode_jenis' => 'JD004',
                'nama_jenis' => 'Bocor pipa/aliran',
                'deskripsi' => 'Keluhan pelanggan terkait bocor pipa atau aliran.',
                'id_prioritas_pengaduan' => null,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_jenis_pengaduan' => Str::uuid(),
                'kode_jenis' => 'JD005',
                'nama_jenis' => 'Tagihan/rekening',
                'deskripsi' => 'Keluhan pelanggan terkait tagihan/rekening air.',
                'id_prioritas_pengaduan' => null,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
            [
                'id_jenis_pengaduan' => Str::uuid(),
                'kode_jenis' => 'JD006',
                'nama_jenis' => 'Lainnya',
                'deskripsi' => 'Keluhan pelanggan terkait masalah lainnya.',
                'id_prioritas_pengaduan' => null,
                'dibuat_oleh' => 'system',
                'dibuat_pada' => now(),
            ],
        ]);
    }
}
