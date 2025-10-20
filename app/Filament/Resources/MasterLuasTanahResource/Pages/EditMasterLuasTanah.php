<?php

namespace App\Filament\Resources\MasterLuasTanahResource\Pages;

use App\Filament\Resources\MasterLuasTanahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterLuasTanah extends EditRecord
{
    protected static string $resource = MasterLuasTanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
