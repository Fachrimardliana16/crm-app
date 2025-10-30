<?php

namespace App\Filament\Resources\AngsuranResource\Pages;

use App\Filament\Resources\AngsuranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Builder;

class ViewAngsuran extends ViewRecord
{
    protected static string $resource = AngsuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    // Optimasi query dengan eager loading yang tepat
    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        return static::getResource()::resolveRecordRouteBinding($key)
            ->load(['rab.pendaftaran', 'rab.pelanggan']);
    }
}
