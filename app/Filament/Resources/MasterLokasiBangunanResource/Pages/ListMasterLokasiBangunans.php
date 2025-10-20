<?php

namespace App\Filament\Resources\MasterLokasiBangunanResource\Pages;

use App\Filament\Resources\MasterLokasiBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterLokasiBangunans extends ListRecords
{
    protected static string $resource = MasterLokasiBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
