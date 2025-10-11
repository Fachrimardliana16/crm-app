<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pelanggan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // Using custom timestamp columns

    protected $fillable = [
        'nomor_pelanggan',
        'nama_pelanggan',
        'nik',
        'jenis_identitas',
        'nomor_identitas',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'nomor_hp',
        'nomor_telepon',
        'email',
        'status_pelanggan',
        'golongan',
        'tipe_pelanggan',
        'segment',
        'id_area',
        'id_spam',
        'latitude',
        'longitude',
        'elevasi',
        'kode_gis',
        'status_gis',
        'tgl_validasi_gis',
        'validasi_gis_oleh',
        'keterangan_gis',
        'keterangan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
        'status_historis',
        'tanggal_nonaktif',
        'tanggal_arsip',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_validasi_gis' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'tanggal_nonaktif' => 'date',
        'tanggal_arsip' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'elevasi' => 'decimal:2',
    ];

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_historis', 'aktif');
    }

    public function scopeByArea($query, $areaId)
    {
        return $query->where('id_area', $areaId);
    }

    public function scopeWithValidGis($query)
    {
        return $query->where('status_gis', 'valid');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nomor_pelanggan',
                'nama_pelanggan',
                'status_pelanggan',
                'latitude',
                'longitude',
                'status_gis'
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Pelanggan has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Helper Methods
    public function getFullAddressAttribute()
    {
        return $this->alamat . ', ' . $this->kelurahan . ', ' . $this->kecamatan . ', ' . $this->kota;
    }

    public function isGisValid()
    {
        return $this->status_gis === 'valid';
    }

    // Relationships
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    public function spam()
    {
        return $this->belongsTo(Spam::class, 'id_spam', 'id_spam');
    }

    public function golonganPelanggan()
    {
        return $this->belongsTo(GolonganPelanggan::class, 'golongan', 'id_golongan');
    }

    public function tagihanBulanan()
    {
        return $this->hasMany(TagihanBulanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
