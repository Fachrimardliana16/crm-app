<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Area extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'area';
    protected $primaryKey = 'id_area';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_area',
        'nama_area',
        'deskripsi_area',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'status_area',
        'koordinat_pusat_lat',
        'koordinat_pusat_lng',
        'radius_coverage',
        'jumlah_pelanggan',
        'kapasitas_maksimal',
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
        'radius_coverage' => 'decimal:2',
        'jumlah_pelanggan' => 'integer',
        'kapasitas_maksimal' => 'integer',
    ];

    // Activity logging configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_area',
                'status_area',
                'jumlah_pelanggan',
                'kapasitas_maksimal',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function pelanggan(): HasMany
    {
        return $this->hasMany(Pelanggan::class, 'id_area', 'id_area');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_area', 'aktif');
    }

    public function scopeByKota($query, $kota)
    {
        return $query->where('kota', $kota);
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('jumlah_pelanggan < kapasitas_maksimal');
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return $this->kelurahan . ', ' . $this->kecamatan . ', ' . $this->kota;
    }

    public function getCapacityPercentageAttribute()
    {
        if ($this->kapasitas_maksimal <= 0) {
            return 0;
        }
        return round(($this->jumlah_pelanggan / $this->kapasitas_maksimal) * 100, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'aktif' => 'success',
            'non_aktif' => 'danger',
            'maintenance' => 'warning',
        ];

        return [
            'label' => ucfirst($this->status_area),
            'color' => $colors[$this->status_area] ?? 'gray',
        ];
    }

    public function getCoordinatesAttribute()
    {
        if ($this->koordinat_pusat_lat && $this->koordinat_pusat_lng) {
            return [
                'lat' => (float) $this->koordinat_pusat_lat,
                'lng' => (float) $this->koordinat_pusat_lng,
                'radius' => (float) $this->radius_coverage,
            ];
        }
        return null;
    }
}
