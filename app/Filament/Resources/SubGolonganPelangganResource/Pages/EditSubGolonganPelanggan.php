<?php

namespace App\Filament\Resources\SubGolonganPelangganResource\Pages;

use App\Filament\Resources\SubGolonganPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubGolonganPelanggan extends EditRecord
{
    protected static string $resource = SubGolonganPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
