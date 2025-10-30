<?php

namespace App\Filament\Resources\AngsuranResource\Widgets;

use App\Models\Angsuran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class AngsuranStatOverview extends BaseWidget
{
    // Cache selama 5 menit untuk performa yang lebih baik
    protected static ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {
        // Cache key berdasarkan tanggal untuk refresh harian
        $cacheKey = 'angsuran_stats_' . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, 300, function () { // Cache 5 menit
            $periodeIni = (int) now()->format('Ym');
            
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
                    
                Stat::make('Terlambat', 
                    Angsuran::where('status_bayar', 'belum_bayar')
                        ->where('tanggal_jatuh_tempo', '<', now())
                        ->count()
                )
                    ->description('Angsuran yang terlambat')
                    ->descriptionIcon('heroicon-o-exclamation-triangle')
                    ->color('danger'),
                    
                Stat::make('Tagihan Bulan Ini', 'Rp ' . number_format(
                    Angsuran::where('periode_tagihan', $periodeIni)
                        ->where('status_bayar', 'belum_bayar')
                        ->sum('nominal_angsuran'), 0, ',', '.'
                ))
                    ->description('Total tagihan periode ' . now()->format('F Y'))
                    ->descriptionIcon('heroicon-o-banknotes')
                    ->color('info'),
            ];
        });
    }
}
