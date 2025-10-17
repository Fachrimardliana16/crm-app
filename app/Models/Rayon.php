<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasGeometry;

class Rayon extends Model
{
    use HasFactory, HasUuids, HasGeometry; // LogsActivity disabled temporarily

    protected $table = 'rayon';
    protected $primaryKey = 'id_rayon';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Using custom timestamp columns

    protected $fillable = [
        'kode_rayon',
        'nama_rayon',
        'deskripsi',
        'wilayah',
        'koordinat_pusat_lat',
        'koordinat_pusat_lng',
        'radius_coverage',
        'jumlah_pelanggan',
        'kapasitas_maksimal',
        'status_aktif',
        'latitude',
        'longitude',
        'polygon_area',
        'location', // Add for map picker
        'keterangan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'koordinat_pusat_lat' => 'decimal:8',
        'koordinat_pusat_lng' => 'decimal:8',
        'radius_coverage' => 'integer',
        'jumlah_pelanggan' => 'integer',
        'kapasitas_maksimal' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->dibuat_oleh = auth()->id();
                $model->dibuat_pada = now();
            } else {
                // For seeding or console commands
                $model->dibuat_oleh = 'system';
                $model->dibuat_pada = now();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->diperbarui_oleh = auth()->id();
                $model->diperbarui_pada = now();
            } else {
                // For seeding or console commands
                $model->diperbarui_oleh = 'system';
                $model->diperbarui_pada = now();
            }
        });
    }

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

    /**
     * Activity Log Configuration
     */
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['kode_rayon', 'nama_rayon', 'deskripsi', 'wilayah', 'status_aktif'])
    //         ->logOnlyDirty()
    //         ->dontLogIfAttributesChangedOnly(['diperbarui_pada', 'diperbarui_oleh'])
    //         ->useLogName('rayon');
    // }

    /**
     * Relationship: Rayon has many Sub Rayons
     */
    public function subRayons(): HasMany
    {
        return $this->hasMany(SubRayon::class, 'id_rayon', 'id_rayon');
    }

    /**
     * Relationship: Rayon has many Pelanggans through Sub Rayons
     */
    public function pelanggans(): HasMany
    {
        return $this->hasMany(Pelanggan::class, 'id_rayon', 'id_rayon');
    }

    /**
     * Scope: Only active rayons
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', 'aktif');
    }

    /**
     * Get next available rayon code
     */
    public static function getNextKodeRayon(): string
    {
        $lastRayon = static::orderBy('kode_rayon', 'desc')->first();
        
        if (!$lastRayon) {
            return '01';
        }
        
        $lastCode = (int) $lastRayon->kode_rayon;
        $nextCode = $lastCode + 1;
        
        return str_pad($nextCode, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Update jumlah pelanggan
     */
    public function updateJumlahPelanggan(): void
    {
        $this->update([
            'jumlah_pelanggan' => $this->pelanggans()->count(),
            'diperbarui_oleh' => auth()->user()?->name ?? 'System',
            'diperbarui_pada' => now(),
        ]);
    }

    /**
     * Check if rayon is at capacity
     */
    public function isAtCapacity(): bool
    {
        if (!$this->kapasitas_maksimal) {
            return false;
        }
        
        return $this->jumlah_pelanggan >= $this->kapasitas_maksimal;
    }
}
