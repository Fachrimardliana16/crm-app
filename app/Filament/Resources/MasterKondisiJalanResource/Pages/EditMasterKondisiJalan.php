<?php

namespace App\Filament\Resources\MasterKondisiJalanResource\Pages;

use App\Filament\Resources\MasterKondisiJalanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterKondisiJalan extends EditRecord
{
    protected static string $resource = MasterKondisiJalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
