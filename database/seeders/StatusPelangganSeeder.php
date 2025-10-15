<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;
use Illuminate\Support\Str;

class StatusPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusPelangganData = [
            [
                'kode_status' => 'BARU',
                'nama_status' => 'Baru',
                'deskripsi_status' => 'Pelanggan baru yang baru saja mendaftar',
                'warna_status' => 'info',
                'urutan_tampil' => 1,
            ],
            [
                'kode_status' => 'AKTIF',
                'nama_status' => 'Aktif',
                'deskripsi_status' => 'Pelanggan aktif dengan layanan berjalan normal',
                'warna_status' => 'success',
                'urutan_tampil' => 2,
            ],
            [
                'kode_status' => 'TUTUP_SEMENTARA',
                'nama_status' => 'Tutup Sementara',
                'deskripsi_status' => 'Pelanggan menutup layanan sementara waktu',
                'warna_status' => 'warning',
                'urutan_tampil' => 3,
            ],
            [
                'kode_status' => 'TUTUP_TETAP',
                'nama_status' => 'Tutup Tetap',
                'deskripsi_status' => 'Pelanggan menutup layanan secara permanen',
                'warna_status' => 'danger',
                'urutan_tampil' => 4,
            ],
            [
                'kode_status' => 'BONGKAR',
                'nama_status' => 'Bongkar',
                'deskripsi_status' => 'Instalasi pelanggan dibongkar',
                'warna_status' => 'gray',
                'urutan_tampil' => 5,
            ],
        ];

        foreach ($statusPelangganData as $data) {
            Status::firstOrCreate(
                [
                    'tabel_referensi' => 'pelanggan',
                    'kode_status' => $data['kode_status']
                ],
                [
                    'id_status' => Str::uuid(),
                    'tabel_referensi' => 'pelanggan',
                    'kode_status' => $data['kode_status'],
                    'nama_status' => $data['nama_status'],
                    'deskripsi_status' => $data['deskripsi_status'],
                    'warna_status' => $data['warna_status'],
                    'urutan_tampil' => $data['urutan_tampil'],
                    'status_aktif' => true,
                    'keterangan' => 'Status pelanggan sistem CRM',
                    'dibuat_oleh' => 'system',
                    'dibuat_pada' => now(),
                ]
            );
        }

        $this->command->info('✅ Berhasil membuat ' . count($statusPelangganData) . ' data Status Pelanggan');
    }
}
