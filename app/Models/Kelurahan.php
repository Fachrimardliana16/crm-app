<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kelurahan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'kelurahan';
    protected $primaryKey = 'id_kelurahan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_kelurahan',
        'nama_kelurahan',
        'id_kecamatan',
        'kode_pos',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode_kelurahan', 'nama_kelurahan', 'id_kecamatan', 'status_aktif'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }
}
