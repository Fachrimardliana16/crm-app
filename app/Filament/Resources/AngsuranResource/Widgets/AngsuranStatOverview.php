<?php

namespace App\Filament\Resources\AngsuranResource\Widgets;

use App\Models\Angsuran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AngsuranStatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Angsuran', Angsuran::count())
                ->description('Total semua angsuran')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('primary'),
                
            Stat::make('Belum Bayar', Angsuran::where('status_bayar', 'belum_bayar')->count())
                ->description('Angsuran yang belum dibayar')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
                
            Stat::make('Sudah Bayar', Angsuran::where('status_bayar', 'sudah_bayar')->count())
                ->description('Angsuran yang sudah dibayar')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
                
            Stat::make('Terlambat', Angsuran::terlambat()->count())
                ->description('Angsuran yang terlambat')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),
                
            Stat::make('Nilai Tagihan Bulan Ini', 'Rp ' . number_format(
                Angsuran::where('periode_tagihan', (int) now()->format('Ym'))
                    ->where('status_bayar', 'belum_bayar')
                    ->sum('nominal_angsuran'), 0, ',', '.'
            ))
                ->description('Total tagihan periode ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('info'),
        ];
    }
}
