<?php

namespace App\Filament\Resources\MasterJenisPengaduanResource\Pages;

use App\Filament\Resources\MasterJenisPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterJenisPengaduan extends EditRecord
{
    protected static string $resource = MasterJenisPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
