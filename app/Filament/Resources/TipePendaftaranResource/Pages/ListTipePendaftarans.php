<?php

namespace App\Filament\Resources\TipePendaftaranResource\Pages;

use App\Filament\Resources\TipePendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipePendaftarans extends ListRecords
{
    protected static string $resource = TipePendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
