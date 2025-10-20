<?php

namespace App\Filament\Resources\MasterLuasBangunanResource\Pages;

use App\Filament\Resources\MasterLuasBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterLuasBangunan extends EditRecord
{
    protected static string $resource = MasterLuasBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
