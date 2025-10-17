<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasGeometry;

class SubRayon extends Model
{
    use HasFactory, HasUuids, HasGeometry; // LogsActivity disabled temporarily

    protected $table = 'sub_rayon';
    protected $primaryKey = 'id_sub_rayon';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Using custom timestamp columns

    protected $fillable = [
        'id_rayon',
        'kode_sub_rayon',
        'nama_sub_rayon',
        'deskripsi',
        'wilayah',
        'koordinat_pusat_lat',
        'koordinat_pusat_lng',
        'radius_coverage',
        'jumlah_pelanggan',
        'kapasitas_maksimal',
        'nomor_pelanggan_terakhir',
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
        'nomor_pelanggan_terakhir' => 'integer',
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
    //         ->logOnly(['id_rayon', 'kode_sub_rayon', 'nama_sub_rayon', 'deskripsi', 'wilayah', 'status_aktif'])
    //         ->logOnlyDirty()
    //         ->dontLogIfAttributesChangedOnly(['diperbarui_pada', 'diperbarui_oleh', 'nomor_pelanggan_terakhir'])
    //         ->useLogName('sub_rayon');
    // }

    /**
     * Relationship: Sub Rayon belongs to Rayon
     */
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class, 'id_rayon', 'id_rayon');
    }

    /**
     * Relationship: Sub Rayon has many Pelanggans
     */
    public function pelanggans(): HasMany
    {
        return $this->hasMany(Pelanggan::class, 'id_sub_rayon', 'id_sub_rayon');
    }

    /**
     * Scope: Only active sub rayons
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', 'aktif');
    }

    /**
     * Scope: Filter by rayon
     */
    public function scopeByRayon($query, $rayonId)
    {
        return $query->where('id_rayon', $rayonId);
    }

    /**
     * Get next available sub rayon code
     */
    public static function getNextKodeSubRayon(): string
    {
        $lastSubRayon = static::orderBy('kode_sub_rayon', 'desc')->first();
        
        if (!$lastSubRayon) {
            return '0001';
        }
        
        $lastCode = (int) $lastSubRayon->kode_sub_rayon;
        $nextCode = $lastCode + 1;
        
        return str_pad($nextCode, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get next nomor pelanggan for this sub rayon
     * Format: {kode_rayon}{last_2_digit_sub_rayon}{nomor_urut}
     * Example: 01020003 (rayon 01, sub rayon 02, urut 0003)
     */
    public function getNextNomorPelanggan(): string
    {
        $this->increment('nomor_pelanggan_terakhir');
        $this->save();
        
        $kodeRayon = $this->rayon->kode_rayon; // 2 digit
        $kodeSubRayonLast2 = substr($this->kode_sub_rayon, -2); // Last 2 digits of 4-digit code
        $nomorUrut = str_pad($this->nomor_pelanggan_terakhir, 4, '0', STR_PAD_LEFT); // 4 digit
        
        return $kodeRayon . $kodeSubRayonLast2 . $nomorUrut;
    }

    /**
     * Update jumlah pelanggan
     */
    public function updateJumlahPelanggan(): void
    {
        $jumlahPelanggan = $this->pelanggans()->count();
        
        $this->update([
            'jumlah_pelanggan' => $jumlahPelanggan,
            'diperbarui_oleh' => auth()->user()?->name ?? 'System',
            'diperbarui_pada' => now(),
        ]);

        // Update parent rayon juga
        $this->rayon?->updateJumlahPelanggan();
    }

    /**
     * Check if sub rayon is at capacity
     */
    public function isAtCapacity(): bool
    {
        if (!$this->kapasitas_maksimal) {
            return false;
        }
        
        return $this->jumlah_pelanggan >= $this->kapasitas_maksimal;
    }

    /**
     * Get formatted display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "[{$this->kode_sub_rayon}] {$this->nama_sub_rayon}";
    }

    /**
     * Get kode gabungan with parent rayon
     */
    public function getKodeGabunganAttribute(): string
    {
        $kodeRayon = $this->rayon?->kode_rayon ?? '00';
        $kodeSubRayonLast2 = substr($this->kode_sub_rayon, -2);
        
        return $kodeRayon . $kodeSubRayonLast2;
    }
}
