<?php

namespace App\Filament\Resources\MasterFungsiRumahResource\Pages;

use App\Filament\Resources\MasterFungsiRumahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterFungsiRumahs extends ListRecords
{
    protected static string $resource = MasterFungsiRumahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
