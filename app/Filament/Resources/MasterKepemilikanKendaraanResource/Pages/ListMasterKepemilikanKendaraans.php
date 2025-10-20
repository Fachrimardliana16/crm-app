<?php

namespace App\Filament\Resources\MasterKepemilikanKendaraanResource\Pages;

use App\Filament\Resources\MasterKepemilikanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterKepemilikanKendaraans extends ListRecords
{
    protected static string $resource = MasterKepemilikanKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
