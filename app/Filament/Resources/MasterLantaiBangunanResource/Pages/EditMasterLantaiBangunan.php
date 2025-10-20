<?php

namespace App\Filament\Resources\MasterLantaiBangunanResource\Pages;

use App\Filament\Resources\MasterLantaiBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterLantaiBangunan extends EditRecord
{
    protected static string $resource = MasterLantaiBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
