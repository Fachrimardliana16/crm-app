<?php

namespace App\Filament\Resources\RincianAngsuranResource\Pages;

use App\Filament\Resources\RincianAngsuranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRincianAngsuran extends EditRecord
{
    protected static string $resource = RincianAngsuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
