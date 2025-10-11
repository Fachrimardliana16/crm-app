<?php

namespace App\Filament\Resources\TipeLayananResource\Pages;

use App\Filament\Resources\TipeLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipeLayanan extends EditRecord
{
    protected static string $resource = TipeLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
