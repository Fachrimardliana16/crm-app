<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisDaftar;

class JenisDaftarSeeder extends Seeder
{
    public function run(): void
    {
        $jenisDaftarData = [
            [
                'kode_jenis_daftar' => 'STD',
                'nama_jenis_daftar' => 'Standar',
                'deskripsi' => 'Pendaftaran dengan prosedur standar dan waktu normal',
                'biaya_tambahan' => 0,
                'lama_proses_hari' => 14,
                'status_aktif' => true,
            ],
            [
                'kode_jenis_daftar' => 'NS',
                'nama_jenis_daftar' => 'Non Standar',
                'deskripsi' => 'Pendaftaran khusus dengan prosedur dan persyaratan tambahan',
                'biaya_tambahan' => 100000,
                'lama_proses_hari' => 21,
                'status_aktif' => true,
            ],
            [
                'kode_jenis_daftar' => 'EXP',
                'nama_jenis_daftar' => 'Express',
                'deskripsi' => 'Pendaftaran cepat dengan biaya tambahan',
                'biaya_tambahan' => 200000,
                'lama_proses_hari' => 7,
                'status_aktif' => true,
            ],
            [
                'kode_jenis_daftar' => 'PRO',
                'nama_jenis_daftar' => 'Prioritas',
                'deskripsi' => 'Pendaftaran prioritas untuk instansi pemerintah atau kebutuhan khusus',
                'biaya_tambahan' => 0,
                'lama_proses_hari' => 5,
                'status_aktif' => true,
            ],
        ];

        foreach ($jenisDaftarData as $data) {
            JenisDaftar::updateOrCreate(
                ['kode_jenis_daftar' => $data['kode_jenis_daftar']],
                $data
            );
        }
    }
}
