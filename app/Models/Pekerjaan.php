<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pekerjaan extends Model
{
    use HasFactory, HasUuids; // , LogsActivity - disabled temporarily

    protected $table = 'pekerjaan';
    protected $primaryKey = 'id_pekerjaan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_pekerjaan',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['nama_pekerjaan', 'status_aktif'])
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }
}
