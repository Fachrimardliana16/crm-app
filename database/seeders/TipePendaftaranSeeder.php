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
                'biaya_admin' => 15000,
                'prioritas' => 3,
                'perlu_survei' => true,
                'otomatis_approve' => false,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_pendaftaran' => 'KIL',
                'nama_tipe_pendaftaran' => 'Kilat',
                'deskripsi' => 'Pendaftaran kilat dengan proses dipercepat',
                'biaya_admin' => 35000,
                'prioritas' => 2,
                'perlu_survei' => true,
                'otomatis_approve' => false,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_pendaftaran' => 'EXP',
                'nama_tipe_pendaftaran' => 'Express',
                'deskripsi' => 'Pendaftaran express dengan proses sangat cepat',
                'biaya_admin' => 50000,
                'prioritas' => 1,
                'perlu_survei' => false,
                'otomatis_approve' => true,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_pendaftaran' => 'ON',
                'nama_tipe_pendaftaran' => 'Online',
                'deskripsi' => 'Pendaftaran melalui sistem online/website',
                'biaya_admin' => 0,
                'prioritas' => 2,
                'perlu_survei' => true,
                'otomatis_approve' => false,
                'status_aktif' => true,
            ],
            [
                'kode_tipe_pendaftaran' => 'INST',
                'nama_tipe_pendaftaran' => 'Instansi',
                'deskripsi' => 'Pendaftaran khusus untuk instansi pemerintah',
                'biaya_admin' => 0,
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
