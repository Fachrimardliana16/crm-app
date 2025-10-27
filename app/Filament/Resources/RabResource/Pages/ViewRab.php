<?php

namespace App\Filament\Resources\RabResource\Pages;

use App\Filament\Resources\RabResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRab extends ViewRecord
{
    protected static string $resource = RabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit RAB')
                ->icon('heroicon-o-pencil'),
        ];
    }
}