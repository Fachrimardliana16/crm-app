<?php

namespace App\Filament\Resources\BacaanMeterResource\Pages;

use App\Filament\Resources\BacaanMeterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBacaanMeters extends ListRecords
{
    protected static string $resource = BacaanMeterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
