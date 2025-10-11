<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BacaanMeter extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'bacaan_meter';
    protected $primaryKey = 'id_bacaan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'periode_bacaan',
        'tanggal_bacaan',
        'nip_petugas_baca',
        'angka_meter_sebelum',
        'angka_meter_sekarang',
        'pemakaian_air',
        'status_bacaan',
        'catatan_bacaan',
        'foto_meter',
        'koordinat_bacaan_lat',
        'koordinat_bacaan_lng',
        'waktu_bacaan',
        'dibuat_oleh',
        'dibuat_pada',
    ];

    protected $casts = [
        'tanggal_bacaan' => 'date',
        'angka_meter_sebelum' => 'integer',
        'angka_meter_sekarang' => 'integer',
        'pemakaian_air' => 'integer',
        'koordinat_bacaan_lat' => 'decimal:8',
        'koordinat_bacaan_lng' => 'decimal:8',
        'waktu_bacaan' => 'datetime',
        'dibuat_pada' => 'datetime',
    ];

    // Relationships
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'angka_meter_sekarang',
                'pemakaian_air',
                'status_bacaan',
                'catatan_bacaan',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
