<?php

namespace App\Filament\Resources\GolonganPelangganResource\Pages;

use App\Filament\Resources\GolonganPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGolonganPelanggans extends ListRecords
{
    protected static string $resource = GolonganPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
