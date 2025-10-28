<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterJenisPengaduan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_jenis_pengaduan';
    protected $primaryKey = 'id_jenis_pengaduan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_jenis',
        'nama_jenis',
        'deskripsi',
        'id_prioritas_pengaduan',
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

    public function prioritas(): BelongsTo
    {
        return $this->belongsTo(MasterPrioritasPengaduan::class, 'id_prioritas_pengaduan', 'id_prioritas_pengaduan');
    }

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'id_jenis_pengaduan', 'id_jenis_pengaduan');
    }
}
