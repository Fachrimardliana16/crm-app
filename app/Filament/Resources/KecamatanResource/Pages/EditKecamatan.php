<?php

namespace App\Filament\Resources\KecamatanResource\Pages;

use App\Filament\Resources\KecamatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKecamatan extends EditRecord
{
    protected static string $resource = KecamatanResource::class;

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
}
