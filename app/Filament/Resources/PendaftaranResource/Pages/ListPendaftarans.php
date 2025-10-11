<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Pendaftaran Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Pendaftaran')
                ->icon('heroicon-o-list-bullet')
                ->badge(fn () => \App\Models\Pendaftaran::count()),

            'belum_pelanggan' => Tab::make('Belum Jadi Pelanggan')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('id_pelanggan'))
                ->badge(fn () => \App\Models\Pendaftaran::whereNull('id_pelanggan')->count())
                ->badgeColor('warning'),

            'sudah_pelanggan' => Tab::make('Sudah Jadi Pelanggan')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('id_pelanggan'))
                ->badge(fn () => \App\Models\Pendaftaran::whereNotNull('id_pelanggan')->count())
                ->badgeColor('success'),

            'bulan_ini' => Tab::make('Bulan Ini')
                ->icon('heroicon-o-calendar-days')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereMonth('tanggal_daftar', now()->month)
                    ->whereYear('tanggal_daftar', now()->year))
                ->badge(fn () => \App\Models\Pendaftaran::whereMonth('tanggal_daftar', now()->month)
                    ->whereYear('tanggal_daftar', now()->year)
                    ->count())
                ->badgeColor('info'),

            'hari_ini' => Tab::make('Hari Ini')
                ->icon('heroicon-o-sun')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('tanggal_daftar', now()))
                ->badge(fn () => \App\Models\Pendaftaran::whereDate('tanggal_daftar', now())->count())
                ->badgeColor('primary'),
        ];
    }
}
