<?php

namespace App\Filament\Resources\TipePendaftaranResource\Pages;

use App\Filament\Resources\TipePendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipePendaftaran extends EditRecord
{
    protected static string $resource = TipePendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
