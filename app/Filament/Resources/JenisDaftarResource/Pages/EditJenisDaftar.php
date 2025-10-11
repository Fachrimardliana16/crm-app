<?php

namespace App\Filament\Resources\JenisDaftarResource\Pages;

use App\Filament\Resources\JenisDaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisDaftar extends EditRecord
{
    protected static string $resource = JenisDaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
