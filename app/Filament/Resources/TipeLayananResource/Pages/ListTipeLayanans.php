<?php

namespace App\Filament\Resources\TipeLayananResource\Pages;

use App\Filament\Resources\TipeLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipeLayanans extends ListRecords
{
    protected static string $resource = TipeLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
