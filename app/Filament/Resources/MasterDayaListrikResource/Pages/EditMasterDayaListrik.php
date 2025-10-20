<?php

namespace App\Filament\Resources\MasterDayaListrikResource\Pages;

use App\Filament\Resources\MasterDayaListrikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterDayaListrik extends EditRecord
{
    protected static string $resource = MasterDayaListrikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
