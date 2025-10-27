<?php

namespace App\Filament\Resources\RabResource\Pages;

use App\Filament\Resources\RabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRabs extends ListRecords
{
    protected static string $resource = RabResource::class;

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Semua')
                ->modifyQueryUsing(fn (Builder $query) => $query),
                
            'draft' => Tab::make('Draft')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_rab', 'draft'))
                ->badge(fn () => \App\Models\Rab::where('status_rab', 'draft')->count()),
                
            'proses' => Tab::make('Proses')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_rab', 'proses'))
                ->badge(fn () => \App\Models\Rab::where('status_rab', 'proses')->count()),
                
            'disetujui' => Tab::make('Disetujui')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_rab', 'disetujui'))
                ->badge(fn () => \App\Models\Rab::where('status_rab', 'disetujui')->count()),
                
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_rab', 'ditolak'))
                ->badge(fn () => \App\Models\Rab::where('status_rab', 'ditolak')->count()),
        ];

        // Tambahkan tab per cabang
        $branches = \App\Models\Cabang::orderBy('nama_cabang')->get();

        foreach ($branches as $branch) {
            $count = \App\Models\Rab::whereHas('pendaftaran', 
                fn ($query) => $query->where('id_cabang', $branch->id_cabang)
            )->count();
                
            if ($count > 0) {
                $tabs['branch_' . $branch->id_cabang] = Tab::make($branch->nama_cabang)
                    ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('pendaftaran', 
                        fn ($q) => $q->where('id_cabang', $branch->id_cabang)
                    ))
                    ->badge($count);
            }
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
