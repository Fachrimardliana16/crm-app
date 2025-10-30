<?php

namespace App\Filament\Resources\SubBagianResource\Pages;

use App\Filament\Resources\SubBagianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubBagians extends ListRecords
{
    protected static string $resource = SubBagianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
