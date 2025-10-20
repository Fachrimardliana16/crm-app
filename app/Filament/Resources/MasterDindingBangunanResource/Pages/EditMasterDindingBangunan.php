<?php

namespace App\Filament\Resources\MasterDindingBangunanResource\Pages;

use App\Filament\Resources\MasterDindingBangunanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterDindingBangunan extends EditRecord
{
    protected static string $resource = MasterDindingBangunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
