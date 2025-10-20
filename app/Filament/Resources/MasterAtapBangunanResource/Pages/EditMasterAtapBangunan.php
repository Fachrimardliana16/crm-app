<?php

namespace App\Filament\Resources\MasterAtapBangunanResource\Pages;

use App\Filament\Resources\MasterAtapBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterAtapBangunan extends EditRecord
{
    protected static string $resource = MasterAtapBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
