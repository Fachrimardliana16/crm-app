<?php

namespace App\Filament\Resources\BacaanMeterResource\Pages;

use App\Filament\Resources\BacaanMeterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBacaanMeter extends EditRecord
{
    protected static string $resource = BacaanMeterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
