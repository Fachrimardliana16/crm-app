<?php

namespace App\Filament\Resources\SubBagianResource\Pages;

use App\Filament\Resources\SubBagianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubBagian extends EditRecord
{
    protected static string $resource = SubBagianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
