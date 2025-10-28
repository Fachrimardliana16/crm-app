<?php

namespace App\Filament\Resources\MasterStatusPengaduanResource\Pages;

use App\Filament\Resources\MasterStatusPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterStatusPengaduan extends EditRecord
{
    protected static string $resource = MasterStatusPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
