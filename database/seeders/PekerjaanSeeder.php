<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pekerjaan;

class PekerjaanSeeder extends Seeder
{
    public function run(): void
    {
        $pekerjaanData = [
            ['nama_pekerjaan' => 'PNS', 'deskripsi' => 'Pegawai Negeri Sipil'],
            ['nama_pekerjaan' => 'TNI/POLRI', 'deskripsi' => 'Tentara Nasional Indonesia / Polisi Republik Indonesia'],
            ['nama_pekerjaan' => 'Swasta', 'deskripsi' => 'Karyawan Swasta'],
            ['nama_pekerjaan' => 'Wiraswasta', 'deskripsi' => 'Wiraswasta/Pengusaha'],
            ['nama_pekerjaan' => 'Petani', 'deskripsi' => 'Petani/Peternak'],
            ['nama_pekerjaan' => 'Buruh', 'deskripsi' => 'Buruh/Pekerja Harian'],
            ['nama_pekerjaan' => 'Pensiunan', 'deskripsi' => 'Pensiunan'],
            ['nama_pekerjaan' => 'Mahasiswa', 'deskripsi' => 'Mahasiswa'],
            ['nama_pekerjaan' => 'Ibu Rumah Tangga', 'deskripsi' => 'Ibu Rumah Tangga'],
            ['nama_pekerjaan' => 'Lainnya', 'deskripsi' => 'Pekerjaan Lainnya'],
        ];

        foreach ($pekerjaanData as $data) {
            Pekerjaan::create($data);
        }
    }
}
