<?php

namespace App\Filament\Resources\CabangResource\Pages;

use App\Filament\Resources\CabangResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditCabang extends EditRecord
{
    protected static string $resource = CabangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Hydrate location data for map field
        if ($this->record && $this->record->polygon_area) {
            try {
                $geometry = json_decode($this->record->polygon_area, true);
                if ($geometry && isset($geometry['type'])) {
                    $data['location'] = [
                        'lat' => $this->record->latitude ?? -7.388119,
                        'lng' => $this->record->longitude ?? 109.358398,
                        'geojson' => [
                            'type' => 'FeatureCollection',
                            'features' => [
                                [
                                    'type' => 'Feature',
                                    'geometry' => $geometry,
                                    'properties' => []
                                ]
                            ]
                        ]
                    ];
                }
            } catch (\Exception $e) {
                // Fallback to just coordinates
                $data['location'] = [
                    'lat' => $this->record->latitude ?? -7.388119,
                    'lng' => $this->record->longitude ?? 109.358398
                ];
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
