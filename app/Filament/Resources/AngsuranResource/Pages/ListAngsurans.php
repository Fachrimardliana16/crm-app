<?php

namespace App\Filament\Resources\AngsuranResource\Pages;

use App\Filament\Resources\AngsuranResource;
use App\Models\Angsuran;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ListAngsurans extends ListRecords
{
    protected static string $resource = AngsuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         AngsuranResource\Widgets\AngsuranStatOverview::class,
    //     ];
    // }

    // Optimasi query untuk loading yang lebih cepat
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with(['rab.pendaftaran'])
            ->latest('created_at');
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'belum_bayar' => Tab::make('Belum Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_bayar', 'belum_bayar'))
                ->badge(fn () => Cache::remember('angsuran_belum_bayar_count', 300, function () {
                    return Angsuran::where('status_bayar', 'belum_bayar')->count();
                })),
            'sudah_bayar' => Tab::make('Sudah Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_bayar', 'sudah_bayar'))
                ->badge(fn () => Cache::remember('angsuran_sudah_bayar_count', 300, function () {
                    return Angsuran::where('status_bayar', 'sudah_bayar')->count();
                })),
            'terlambat' => Tab::make('Terlambat')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status_bayar', 'belum_bayar')
                          ->where('tanggal_jatuh_tempo', '<', now())
                )
                ->badge(fn () => Cache::remember('angsuran_terlambat_count', 300, function () {
                    return Angsuran::where('status_bayar', 'belum_bayar')
                        ->where('tanggal_jatuh_tempo', '<', now())
                        ->count();
                })),
        ];
    }
}
