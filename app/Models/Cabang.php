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
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

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
