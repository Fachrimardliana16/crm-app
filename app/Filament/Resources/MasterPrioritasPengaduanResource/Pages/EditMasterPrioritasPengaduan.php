<?php

namespace App\Filament\Resources\MasterPrioritasPengaduanResource\Pages;

use App\Filament\Resources\MasterPrioritasPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPrioritasPengaduan extends EditRecord
{
    protected static string $resource = MasterPrioritasPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
