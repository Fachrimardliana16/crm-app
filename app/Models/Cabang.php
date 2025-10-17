<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasGeometry;

class Cabang extends Model
{
    use HasFactory, HasUuids, HasGeometry; // , LogsActivity - disabled temporarily

    protected $table = 'cabang';
    protected $primaryKey = 'id_cabang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'wilayah_pelayanan',
        'alamat',
        'telepon',
        'email',
        'kepala_cabang',
        'status_aktif',
        'keterangan',
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

    // Accessor dan Mutator untuk location field
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

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['kode_cabang', 'nama_cabang', 'status_aktif'])
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }

    // Relationships
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_cabang', 'id_cabang');
    }
}
