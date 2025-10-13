<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipePendaftaran;

class TipePendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $tipePendaftaranData = [
            [
                'kode_tipe_pendaftaran' => 'REG',
                'nama_tipe_pendaftaran' => 'Reguler',
                'deskripsi' => 'Pendaftaran reguler dengan proses standar',
                'biaya_admin' => 0,
                'prioritas' => 2,
                'perlu_survei' => true,
                'otomatis_approve' => false,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_pendaftaran' => 'KIL',
                'nama_tipe_pendaftaran' => 'Kilat',
                'deskripsi' => 'Pendaftaran kilat dengan proses dipercepat',
                'biaya_admin' => 100000,
                'prioritas' => 1,
                'perlu_survei' => true,
                'otomatis_approve' => false,
                'status_aktif' => true,
            ],
        ];

        foreach ($tipePendaftaranData as $data) {
            TipePendaftaran::updateOrCreate(
                ['kode_tipe_pendaftaran' => $data['kode_tipe_pendaftaran']],
                $data
            );
        }
    }
}
