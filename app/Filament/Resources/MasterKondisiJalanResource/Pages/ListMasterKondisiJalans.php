<?php

namespace App\Filament\Resources\MasterKondisiJalanResource\Pages;

use App\Filament\Resources\MasterKondisiJalanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterKondisiJalans extends ListRecords
{
    protected static string $resource = MasterKondisiJalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
