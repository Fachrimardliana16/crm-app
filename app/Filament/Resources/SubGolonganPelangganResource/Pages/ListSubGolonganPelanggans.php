<?php

namespace App\Filament\Resources\SubGolonganPelangganResource\Pages;

use App\Filament\Resources\SubGolonganPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubGolonganPelanggans extends ListRecords
{
    protected static string $resource = SubGolonganPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
