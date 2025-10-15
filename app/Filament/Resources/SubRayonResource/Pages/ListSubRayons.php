<?php

namespace App\Filament\Resources\SubRayonResource\Pages;

use App\Filament\Resources\SubRayonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubRayons extends ListRecords
{
    protected static string $resource = SubRayonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
