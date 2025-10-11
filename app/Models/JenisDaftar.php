<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class JenisDaftar extends Model
{
    use HasFactory, HasUuids; // , LogsActivity - disabled temporarily

    protected $table = 'jenis_daftar';
    protected $primaryKey = 'id_jenis_daftar';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_jenis_daftar',
        'nama_jenis_daftar',
        'deskripsi',
        'biaya_tambahan',
        'lama_proses_hari',
        'status_aktif',
    ];

    protected $casts = [
        'biaya_tambahan' => 'decimal:2',
        'lama_proses_hari' => 'integer',
        'status_aktif' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_jenis_daftar', 'biaya', 'status_aktif'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_jenis_daftar', 'id_jenis_daftar');
    }
}
