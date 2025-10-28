<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterStatusPengaduan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_status_pengaduan';
    protected $primaryKey = 'id_status_pengaduan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_status',
        'nama_status',
        'tahap',
        'is_final',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'is_final' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // === Relationships ===

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'id_status_pengaduan', 'id_status_pengaduan');
    }
}
