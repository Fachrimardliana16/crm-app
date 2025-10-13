<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class GolonganPelanggan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'golongan_pelanggan';
    protected $primaryKey = 'id_golongan_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_golongan',
        'nama_golongan',
        'deskripsi',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Relationships
    public function subGolongan(): HasMany
    {
        return $this->hasMany(SubGolonganPelanggan::class, 'id_golongan_pelanggan', 'id_golongan_pelanggan')
                    ->orderBy('urutan');
    }

    public function subGolonganAktif(): HasMany
    {
        return $this->subGolongan()->where('is_active', true);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUrutan($query)
    {
        return $query->orderBy('urutan');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return [
            'label' => $this->is_active ? 'Aktif' : 'Non-Aktif',
            'color' => $this->is_active ? 'success' : 'danger',
        ];
    }

    public function getNamaLengkapAttribute()
    {
        return $this->kode_golongan . ' - ' . $this->nama_golongan;
    }
}
