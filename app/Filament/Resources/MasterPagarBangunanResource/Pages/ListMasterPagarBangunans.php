<?php

namespace App\Filament\Resources\MasterPagarBangunanResource\Pages;

use App\Filament\Resources\MasterPagarBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPagarBangunans extends ListRecords
{
    protected static string $resource = MasterPagarBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
