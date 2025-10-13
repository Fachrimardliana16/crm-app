<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pendaftaran;
use App\Models\Pelanggan;
use App\Models\Pengaduan;
use App\Models\TagihanBulanan;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Safe counting dengan try-catch untuk tabel yang mungkin belum ada
        $totalPelanggan = $this->safeCount(Pelanggan::class);
        $pendaftaranAktif = $this->safeCount(Pendaftaran::class, fn($q) =>
            $q->whereNotIn('status_pendaftaran', ['selesai', 'ditolak']));
        $pengaduanTerbuka = $this->safeCount(Pengaduan::class, fn($q) =>
            $q->whereNotIn('status_pengaduan', ['selesai', 'ditutup']));
        $tagihanBulanIni = $this->safeCount(TagihanBulanan::class, fn($q) =>
            $q->where('periode_tagihan', now()->format('Y-m')));

        return [
            Stat::make('Total Pelanggan', $totalPelanggan)
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Pendaftaran Aktif', $pendaftaranAktif)
                ->description('Dalam proses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pengaduan Terbuka', $pengaduanTerbuka)
                ->description('Perlu penanganan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Tagihan Bulan Ini', $tagihanBulanIni)
                ->description('Tagihan periode ' . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }

    private function safeCount($modelClass, $callback = null)
    {
        try {
            $query = $modelClass::query();
            if ($callback) {
                $callback($query);
            }
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
