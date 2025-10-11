<?php

namespace App\Filament\Resources\InstalasiResource\Pages;

use App\Filament\Resources\InstalasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstalasis extends ListRecords
{
    protected static string $resource = InstalasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
