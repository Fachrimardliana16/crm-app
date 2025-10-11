<?php

namespace App\Filament\Resources\RincianAngsuranResource\Pages;

use App\Filament\Resources\RincianAngsuranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRincianAngsurans extends ListRecords
{
    protected static string $resource = RincianAngsuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
