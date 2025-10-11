<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kecamatan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'kecamatan';
    protected $primaryKey = 'id_kecamatan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_kecamatan',
        'nama_kecamatan',
        'kota',
        'provinsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode_kecamatan', 'nama_kecamatan', 'status_aktif'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id_kecamatan');
    }
}
