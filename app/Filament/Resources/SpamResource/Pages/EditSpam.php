<?php

namespace App\Filament\Resources\SpamResource\Pages;

use App\Filament\Resources\SpamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpam extends EditRecord
{
    protected static string $resource = SpamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
