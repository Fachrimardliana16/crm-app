<?php

namespace App\Filament\Resources\AngsuranResource\Pages;

use App\Filament\Resources\AngsuranResource;
use App\Models\Angsuran;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAngsurans extends ListRecords
{
    protected static string $resource = AngsuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AngsuranResource\Widgets\AngsuranStatOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'belum_bayar' => Tab::make('Belum Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_bayar', 'belum_bayar'))
                ->badge(Angsuran::where('status_bayar', 'belum_bayar')->count()),
            'sudah_bayar' => Tab::make('Sudah Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_bayar', 'sudah_bayar'))
                ->badge(Angsuran::where('status_bayar', 'sudah_bayar')->count()),
            'terlambat' => Tab::make('Terlambat')
                ->modifyQueryUsing(fn (Builder $query) => $query->terlambat())
                ->badge(Angsuran::terlambat()->count()),
        ];
    }
}
