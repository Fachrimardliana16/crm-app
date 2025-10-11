<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipeLayanan extends Model
{
    use HasFactory, HasUuids; // , LogsActivity - disabled temporarily

    protected $table = 'tipe_layanan';
    protected $primaryKey = 'id_tipe_layanan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_tipe_layanan',
        'nama_tipe_layanan',
        'deskripsi',
        'biaya_standar',
        'status_aktif',
    ];

    protected $casts = [
        'biaya_standar' => 'decimal:2',
        'status_aktif' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_tipe_layanan', 'status_aktif'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_tipe_layanan', 'id_tipe_layanan');
    }
}
