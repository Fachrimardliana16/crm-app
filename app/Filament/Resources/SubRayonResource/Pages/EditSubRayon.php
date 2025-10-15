<?php

namespace App\Filament\Resources\SubRayonResource\Pages;

use App\Filament\Resources\SubRayonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubRayon extends EditRecord
{
    protected static string $resource = SubRayonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
