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
    use HasFactory, HasUuids, LogsActivity, HasGeometry;

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

    // Activity logging configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_spam',
                'status_operasional',
                'kapasitas_produksi',
                'sumber_air',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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
