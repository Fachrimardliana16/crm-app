<?php

namespace App\Filament\Resources\MasterPrioritasPengaduanResource\Pages;

use App\Filament\Resources\MasterPrioritasPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPrioritasPengaduans extends ListRecords
{
    protected static string $resource = MasterPrioritasPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->icon('heroicon-s-plus')
                ->color('primary'),
        ];
    }
}
