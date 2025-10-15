<?php

namespace App\Filament\Resources\DanameterResource\Pages;

use App\Filament\Resources\DanameterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDanameters extends ListRecords
{
    protected static string $resource = DanameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
