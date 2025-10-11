<?php

namespace App\Filament\Resources\InstalasiResource\Pages;

use App\Filament\Resources\InstalasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstalasi extends EditRecord
{
    protected static string $resource = InstalasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
