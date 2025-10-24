<?php

namespace App\Filament\Resources\PendaftaranResource\Widgets;

use App\Models\Pendaftaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendaftaranStatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Statistik Tahunan
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        $yearly = Pendaftaran::selectRaw("EXTRACT(YEAR FROM tanggal_daftar) as year, COUNT(*) as total")
            ->groupByRaw('EXTRACT(YEAR FROM tanggal_daftar)')
            ->orderBy('year', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->year => $item->total];
            });

        $totalYearly = $yearly->sum();
        $yearlyCurrent = $yearly[$currentYear] ?? 0;
        $yearlyPrevious = $yearly[$previousYear] ?? 0;
        $yearlyChange = $yearlyPrevious > 0 ? (($yearlyCurrent - $yearlyPrevious) / $yearlyPrevious * 100) : ($yearlyCurrent > 0 ? 100 : 0);
        $yearlyDescription = $yearlyCurrent > 0 ? ($yearlyChange >= 0 ? "Naik " . number_format($yearlyChange, 1) . "% dari tahun lalu" : "Turun " . number_format(abs($yearlyChange), 1) . "% dari tahun lalu") : 'Tidak ada data tahun ini';

        // Statistik Bulanan (tahun ini)
        $currentMonth = date('m');
        $previousMonth = date('m', strtotime('-1 month'));
        $monthly = Pendaftaran::selectRaw("EXTRACT(MONTH FROM tanggal_daftar) as month, COUNT(*) as total")
            ->whereRaw("EXTRACT(YEAR FROM tanggal_daftar) = ?", [$currentYear])
            ->groupByRaw('EXTRACT(MONTH FROM tanggal_daftar)')
            ->orderBy('month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->total];
            });

        $totalMonthly = $monthly->sum();
        $monthlyCurrent = $monthly[$currentMonth] ?? 0;
        $monthlyPrevious = $monthly[$previousMonth] ?? 0;
        $monthlyChange = $monthlyPrevious > 0 ? (($monthlyCurrent - $monthlyPrevious) / $monthlyPrevious * 100) : ($monthlyCurrent > 0 ? 100 : 0);
        $monthlyDescription = $monthlyCurrent > 0 ? ($monthlyChange >= 0 ? "Naik " . number_format($monthlyChange, 1) . "% dari bulan lalu" : "Turun " . number_format(abs($monthlyChange), 1) . "% dari bulan lalu") : 'Tidak ada data bulan ini';

        // Statistik Harian (bulan ini)
        $currentDay = date('d');
        $previousDay = date('d', strtotime('-1 day'));
        $daily = Pendaftaran::selectRaw("EXTRACT(DAY FROM tanggal_daftar) as day, COUNT(*) as total")
            ->whereRaw("EXTRACT(YEAR FROM tanggal_daftar) = ? AND EXTRACT(MONTH FROM tanggal_daftar) = ?", [$currentYear, $currentMonth])
            ->groupByRaw('EXTRACT(DAY FROM tanggal_daftar)')
            ->orderBy('day', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->day => $item->total];
            });

        $totalDaily = $daily->sum();
        $dailyCurrent = $daily[$currentDay] ?? 0;
        $dailyPrevious = $daily[$previousDay] ?? 0;
        $dailyChange = $dailyPrevious > 0 ? (($dailyCurrent - $dailyPrevious) / $dailyPrevious * 100) : ($dailyCurrent > 0 ? 100 : 0);
        $dailyDescription = $dailyCurrent > 0 ? ($dailyChange >= 0 ? "Naik " . number_format($dailyChange, 1) . "% dari hari lalu" : "Turun " . number_format(abs($dailyChange), 1) . "% dari hari lalu") : 'Tidak ada data hari ini';

        return [
            Stat::make('Pendaftaran Tahunan', $totalYearly)
                ->description($yearlyDescription)
                ->descriptionIcon('heroicon-m-chart-bar', 'before')
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'shadow-lg rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 transition-transform hover:scale-105',
                ])
                ->chart(array_values($yearly->take(5)->toArray())),
            Stat::make('Pendaftaran Bulanan', $totalMonthly)
                ->description($monthlyDescription)
                ->descriptionIcon('heroicon-m-chart-pie', 'before')
                ->icon('heroicon-o-users')
                ->color('success')
                ->extraAttributes([
                    'class' => 'shadow-lg rounded-xl bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 transition-transform hover:scale-105',
                ])
                ->chart(array_values($monthly->toArray())),
            Stat::make('Pendaftaran Harian', $totalDaily)
                ->description($dailyDescription)
                ->descriptionIcon('heroicon-m-chart-bar', 'before') // Diganti ke ikon valid
                ->icon('heroicon-o-user-plus')
                ->color('info')
                ->extraAttributes([
                    'class' => 'shadow-lg rounded-xl bg-gradient-to-r from-cyan-50 to-cyan-100 dark:from-cyan-900 dark:to-cyan-800 transition-transform hover:scale-105',
                ])
                ->chart(array_values($daily->toArray())),
        ];
    }

    protected function getCharts(): array
    {
        // Data untuk grafik tahunan
        $yearlyData = Pendaftaran::selectRaw("EXTRACT(YEAR FROM tanggal_daftar) as year, COUNT(*) as total")
            ->groupByRaw('EXTRACT(YEAR FROM tanggal_daftar)')
            ->orderBy('year', 'asc')
            ->get()
            ->pluck('total', 'year')
            ->toArray();

        return [
            'yearly_chart' => [
                'type' => 'line',
                'data' => [
                    'labels' => array_keys($yearlyData),
                    'datasets' => [
                        [
                            'label' => 'Jumlah Pendaftaran per Tahun',
                            'data' => array_values($yearlyData),
                            'backgroundColor' => 'rgba(59, 130, 246, 0.3)', // Latar transparan untuk efek gelombang
                            'borderColor' => '#3b82f6', // Biru cerah
                            'borderWidth' => 2,
                            'fill' => true, // Mengisi area di bawah garis
                            'tension' => 0.4, // Kurva halus untuk efek gelombang
                            'pointBackgroundColor' => '#1e40af',
                            'pointBorderColor' => '#ffffff',
                            'pointHoverBackgroundColor' => '#1e40af',
                            'pointHoverBorderColor' => '#ffffff',
                            'pointRadius' => 4,
                            'pointHoverRadius' => 6,
                        ],
                    ],
                ],
                'options' => [
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                            'title' => ['display' => true, 'text' => 'Jumlah Pendaftaran'],
                            'grid' => ['color' => 'rgba(0, 0, 0, 0.1)'],
                        ],
                        'x' => [
                            'title' => ['display' => true, 'text' => 'Tahun'],
                            'grid' => ['display' => false],
                        ],
                    ],
                    'plugins' => [
                        'legend' => ['display' => true, 'position' => 'top'],
                        'title' => ['display' => true, 'text' => 'Tren Pendaftaran Tahunan', 'font' => ['size' => 16]],
                        'tooltip' => ['enabled' => true],
                    ],
                    'animation' => [
                        'duration' => 1000,
                        'easing' => 'easeOutQuart',
                    ],
                ],
            ],
        ];
    }
}
