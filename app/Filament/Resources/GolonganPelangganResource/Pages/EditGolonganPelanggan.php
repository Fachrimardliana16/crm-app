<?php

namespace App\Filament\Resources\GolonganPelangganResource\Pages;

use App\Filament\Resources\GolonganPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGolonganPelanggan extends EditRecord
{
    protected static string $resource = GolonganPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
