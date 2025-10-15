<?php

namespace App\Filament\Resources\DanameterResource\Pages;

use App\Filament\Resources\DanameterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDanameter extends EditRecord
{
    protected static string $resource = DanameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
