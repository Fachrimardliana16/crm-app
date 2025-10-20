<?php

namespace App\Filament\Resources\MasterLuasTanahResource\Pages;

use App\Filament\Resources\MasterLuasTanahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterLuasTanahs extends ListRecords
{
    protected static string $resource = MasterLuasTanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
