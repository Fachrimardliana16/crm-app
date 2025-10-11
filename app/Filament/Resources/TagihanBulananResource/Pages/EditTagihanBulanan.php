<?php

namespace App\Filament\Resources\TagihanBulananResource\Pages;

use App\Filament\Resources\TagihanBulananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTagihanBulanan extends EditRecord
{
    protected static string $resource = TagihanBulananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
