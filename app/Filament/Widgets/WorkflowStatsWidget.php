<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

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
