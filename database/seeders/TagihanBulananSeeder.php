<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TagihanBulanan;
use App\Models\Pelanggan;
use Illuminate\Support\Str;

class TagihanBulananSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Ambil beberapa pelanggan untuk dibuatkan tagihan
        $pelanggans = Pelanggan::limit(10)->get();

        foreach ($pelanggans as $pelanggan) {
            // Buat tagihan untuk 3 periode terakhir
            for ($i = 2; $i >= 0; $i--) {
                $periode = now()->subMonths($i)->format('Y-m');
                $tanggalTerbit = now()->subMonths($i)->startOfMonth()->addDays(20);
                $jatuhTempo = $tanggalTerbit->copy()->addDays(30);

                $pemakaianAir = rand(10, 50); // m3
                $tarifDasar = 3500; // per m3
                $biayaPemakaian = $pemakaianAir * $tarifDasar;
                $biayaBeban = 15000;
                $biayaAdmin = 5000;
                $biayaPemeliharaan = 2000;
                $biayaMeter = 1000;
                $biayaDenda = $i > 0 ? 0 : rand(0, 50000); // denda hanya untuk bulan terakhir

                $totalTagihan = $biayaPemakaian + $biayaBeban + $biayaAdmin + $biayaPemeliharaan + $biayaMeter + $biayaDenda;

                TagihanBulanan::create([
                    'id_tagihan_bulanan' => Str::uuid(),
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'periode_tagihan' => $periode,
                    'tanggal_terbit' => $tanggalTerbit,
                    'jatuh_tempo' => $jatuhTempo,
                    'pemakaian_air' => $pemakaianAir,
                    'tarif_dasar' => $tarifDasar,
                    'biaya_pemakaian' => $biayaPemakaian,
                    'biaya_beban' => $biayaBeban,
                    'biaya_administrasi' => $biayaAdmin,
                    'biaya_pemeliharaan' => $biayaPemeliharaan,
                    'biaya_meter' => $biayaMeter,
                    'biaya_denda' => $biayaDenda,
                    'total_tagihan' => $totalTagihan,
                    'status_pembayaran' => $i === 0 ? 'belum_bayar' : 'belum_bayar',
                    'dibuat_oleh' => 'system',
                    'dibuat_pada' => now(),
                ]);
            }
        }
    }
}
