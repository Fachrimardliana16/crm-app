<?php

namespace App\Filament\Resources\RabResource\Pages;

use App\Filament\Resources\RabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRabs extends ListRecords
{
    protected static string $resource = RabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
