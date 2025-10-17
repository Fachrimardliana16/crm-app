<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasGeometry;

class Spam extends Model
{
    use HasFactory, HasUuids, HasGeometry; // LogsActivity disabled temporarily

    protected $table = 'spam';
    protected $primaryKey = 'id_spam';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_spam',
        'nama_spam',
        'alamat_spam',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'fax',
        'email',
        'website',
        'kapasitas_produksi',
        'status_operasional',
        'tanggal_operasional',
        'sumber_air',
        'keterangan',
        'latitude',
        'longitude',
        'polygon_area',
        'location', // Add for map picker
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_operasional' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'kapasitas_produksi' => 'decimal:2',
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

    // Activity logging configuration
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly([
    //             'nama_spam',
    //             'status_operasional',
    //             'kapasitas_produksi',
    //             'sumber_air',
    //         ])
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }

    // Relationships
    public function pelanggan(): HasMany
    {
        return $this->hasMany(Pelanggan::class, 'id_spam', 'id_spam');
    }

    public function survei(): HasMany
    {
        return $this->hasMany(Survei::class, 'id_spam', 'id_spam');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_operasional', 'aktif');
    }

    public function scopeByKota($query, $kota)
    {
        return $query->where('kota', $kota);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return $this->alamat_spam . ', ' . $this->kelurahan . ', ' . $this->kecamatan . ', ' . $this->kota;
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'aktif' => 'success',
            'non_aktif' => 'danger',
            'maintenance' => 'warning',
        ];

        return [
            'label' => ucfirst($this->status_operasional),
            'color' => $colors[$this->status_operasional] ?? 'gray',
        ];
    }
}
