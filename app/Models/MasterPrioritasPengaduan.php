<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterPrioritasPengaduan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_prioritas_pengaduan';
    protected $primaryKey = 'id_prioritas_pengaduan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_prioritas',
        'nama_prioritas',
        'sla_jam',
        'warna_tampilan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // === Relationships ===

    public function jenis_pengaduan(): HasMany
    {
        return $this->hasMany(MasterJenisPengaduan::class, 'id_prioritas_pengaduan', 'id_prioritas_pengaduan');
    }

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'id_prioritas_pengaduan', 'id_prioritas_pengaduan');
    }
}
