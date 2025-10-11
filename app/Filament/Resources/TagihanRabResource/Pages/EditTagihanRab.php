<?php

namespace App\Filament\Resources\TagihanRabResource\Pages;

use App\Filament\Resources\TagihanRabResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTagihanRab extends EditRecord
{
    protected static string $resource = TagihanRabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
