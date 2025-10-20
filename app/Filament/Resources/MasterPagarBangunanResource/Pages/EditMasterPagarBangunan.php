<?php

namespace App\Filament\Resources\MasterPagarBangunanResource\Pages;

use App\Filament\Resources\MasterPagarBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPagarBangunan extends EditRecord
{
    protected static string $resource = MasterPagarBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
