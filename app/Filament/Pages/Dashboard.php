<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pendaftaran;
use App\Models\Pelanggan;
use App\Models\Pengaduan;
use App\Models\TagihanBulanan;
use Illuminate\Support\Facades\DB;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard PDAM CRM';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = 0;

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStatsWidget::class,
            WorkflowStatsWidget::class,
        ];
    }
}

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Safe counting with try-catch untuk tabel yang mungkin belum ada
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

class WorkflowStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            $workflowStats = Pendaftaran::select('status_pendaftaran', DB::raw('count(*) as total'))
                ->groupBy('status_pendaftaran')
                ->pluck('total', 'status_pendaftaran');
        } catch (\Exception $e) {
            $workflowStats = collect();
        }

        return [
            Stat::make('Draft', $workflowStats['draft'] ?? 0)
                ->description('Menunggu review')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),

            Stat::make('Tahap Survei', $workflowStats['survei'] ?? 0)
                ->description('Proses survei lapangan')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),

            Stat::make('Tahap RAB', $workflowStats['rab'] ?? 0)
                ->description('Pembuatan anggaran')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('Tahap Instalasi', $workflowStats['instalasi'] ?? 0)
                ->description('Proses pemasangan')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('primary'),
        ];
    }
}
