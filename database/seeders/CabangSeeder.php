<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $cabangData = [
            [
                'kode_cabang' => 'CKB',
                'nama_cabang' => 'Cabang Kota Bangga',
                'wilayah_pelayanan' => 'Purbalingga',
                'alamat' => 'Jl. Raya Kota Bangga No. 1',
                'telepon' => '0281-891706',
                'email' => 'ckb@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Cabang utama wilayah Kota Bangga'
            ],
            [
                'kode_cabang' => 'CJS',
                'nama_cabang' => 'Cabang Jendral Soedirman',
                'wilayah_pelayanan' => 'Kalimanah, Padamara',
                'alamat' => 'Jl. Jendral Soedirman No. 25',
                'telepon' => '0281-891706',
                'email' => 'cjs@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Cabang wilayah Jendral Soedirman'
            ],
            [
                'kode_cabang' => 'CUJ',
                'nama_cabang' => 'Cabang Usman Janatin',
                'wilayah_pelayanan' => 'Mrebet, Bobotsari',
                'alamat' => 'Jl. Usman Janatin No. 15',
                'telepon' => '0281-891706',
                'email' => 'cuj@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Cabang wilayah Usman Janatin'
            ],
            [
                'kode_cabang' => 'CAR',
                'nama_cabang' => 'Cabang Ardilawet',
                'wilayah_pelayanan' => 'Kutasari, Bojongsari',
                'alamat' => 'Jl. Ardilawet No. 10',
                'telepon' => '0281-891706',
                'email' => 'car@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Cabang wilayah Ardilawet'
            ],
            [
                'kode_cabang' => 'CGD',
                'nama_cabang' => 'Cabang Goentoer Djarjono',
                'wilayah_pelayanan' => 'Kaligondang',
                'alamat' => 'Jl. Goentoer Djarjono No. 20',
                'telepon' => '0281-891706',
                'email' => 'cgd@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Cabang wilayah Goentoer Djarjono'
            ],
            [
                'kode_cabang' => 'UKM',
                'nama_cabang' => 'Unit IKK Kemangkon',
                'wilayah_pelayanan' => 'Kemangkon',
                'alamat' => 'Jl. Raya Kemangkon No. 5',
                'telepon' => '0281-891706',
                'email' => 'ukm@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Unit IKK wilayah Kemangkon'
            ],
            [
                'kode_cabang' => 'UBK',
                'nama_cabang' => 'Unit IKK Bukateja',
                'wilayah_pelayanan' => 'Bukateja',
                'alamat' => 'Jl. Raya Bukateja No. 8',
                'telepon' => '0281-891706',
                'email' => 'ubk@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Unit IKK wilayah Bukateja'
            ],
            [
                'kode_cabang' => 'URB',
                'nama_cabang' => 'Unit IKK Rembang',
                'wilayah_pelayanan' => 'Rembang',
                'alamat' => 'Jl. Raya Rembang No. 12',
                'telepon' => '0281-891706',
                'email' => 'urb@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Unit IKK wilayah Rembang'
            ],
            [
                'kode_cabang' => 'UKR',
                'nama_cabang' => 'Unit IKK Karangreja',
                'wilayah_pelayanan' => 'Karangreja',
                'alamat' => 'Jl. Raya Karangreja No. 7',
                'telepon' => '0281-891706',
                'email' => 'ukr@pdam.go.id',
                'kepala_cabang' => '-',
                'status_aktif' => true,
                'keterangan' => 'Unit IKK wilayah Karangreja'
            ],
        ];

        foreach ($cabangData as $data) {
            Cabang::updateOrCreate(
                ['kode_cabang' => $data['kode_cabang']],
                $data
            );
        }
    }
}
