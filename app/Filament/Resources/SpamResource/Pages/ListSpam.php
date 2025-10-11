<?php

namespace App\Filament\Resources\SpamResource\Pages;

use App\Filament\Resources\SpamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpam extends ListRecords
{
    protected static string $resource = SpamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
