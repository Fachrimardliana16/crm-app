<?php

namespace App\Filament\Resources\MasterJenisPengaduanResource\Pages;

use App\Filament\Resources\MasterJenisPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterJenisPengaduans extends ListRecords
{
    protected static string $resource = MasterJenisPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
