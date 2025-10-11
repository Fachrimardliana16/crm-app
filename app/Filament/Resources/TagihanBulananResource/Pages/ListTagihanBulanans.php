<?php

namespace App\Filament\Resources\TagihanBulananResource\Pages;

use App\Filament\Resources\TagihanBulananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTagihanBulanans extends ListRecords
{
    protected static string $resource = TagihanBulananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
