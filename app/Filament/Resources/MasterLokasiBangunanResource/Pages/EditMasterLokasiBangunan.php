<?php

namespace App\Filament\Resources\MasterLokasiBangunanResource\Pages;

use App\Filament\Resources\MasterLokasiBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterLokasiBangunan extends EditRecord
{
    protected static string $resource = MasterLokasiBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
