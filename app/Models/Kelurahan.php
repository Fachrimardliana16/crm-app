<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasGeometry;

class Kelurahan extends Model
{
    use HasFactory, HasUuids, HasGeometry; // LogsActivity disabled temporarily

    protected $table = 'kelurahan';
    protected $primaryKey = 'id_kelurahan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_kelurahan',
        'nama_kelurahan',
        'id_kecamatan',
        'kode_pos',
        'status_aktif',
        'latitude',
        'longitude',
        'polygon_area',
        'location', // Add for map picker
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Accessor untuk location field (untuk map picker)
    public function getLocationAttribute()
    {
        $result = [
            'lat' => $this->latitude ?? -7.388119,
            'lng' => $this->longitude ?? 109.358398,
        ];

        if ($this->polygon_area) {
            try {
                $geometry = json_decode($this->polygon_area, true);
                if ($geometry && isset($geometry['type'])) {
                    // Return as FeatureCollection untuk compatibility dengan GeoMan
                    $result['geojson'] = [
                        'type' => 'FeatureCollection',
                        'features' => [
                            [
                                'type' => 'Feature',
                                'geometry' => $geometry,
                                'properties' => []
                            ]
                        ]
                    ];
                }
            } catch (\Exception $e) {
                // Ignore error, just return lat/lng
            }
        }

        return $result;
    }

    // Mutator untuk menyimpan data GeoJSON polygon
    public function setLocationAttribute($value)
    {
        if (is_array($value) && isset($value['geojson'])) {
            $geojson = $value['geojson'];
            
            if (isset($geojson['type'])) {
                if ($geojson['type'] === 'FeatureCollection' && isset($geojson['features'][0]['geometry'])) {
                    // Extract first geometry from FeatureCollection
                    $geometry = $geojson['features'][0]['geometry'];
                    $this->attributes['polygon_area'] = json_encode($geometry);
                } elseif ($geojson['type'] === 'Feature' && isset($geojson['geometry'])) {
                    // Extract geometry from Feature
                    $geometry = $geojson['geometry'];
                    $this->attributes['polygon_area'] = json_encode($geometry);
                } elseif (in_array($geojson['type'], ['Polygon', 'Point', 'LineString', 'MultiPolygon'])) {
                    // Already a geometry object
                    $this->attributes['polygon_area'] = json_encode($geojson);
                }
            }
        }
    }

    // Relationships
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }
}
