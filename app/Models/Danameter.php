<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Danameter extends Model
{
    use HasFactory;

    protected $table = 'danameter';
    protected $primaryKey = 'id_danameter';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_danameter',
        'kode_danameter',
        'diameter_pipa',
        'tarif_danameter',
        'deskripsi',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'id_danameter' => 'string',
        'tarif_danameter' => 'decimal:2',
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_danameter)) {
                $model->id_danameter = (string) Str::uuid();
            }
        });
    }

    // Scope untuk data aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('kode_danameter');
    }

    // Accessor untuk format tarif
    public function getFormattedTarifAttribute()
    {
        return 'Rp ' . number_format((float) $this->tarif_danameter, 0, ',', '.');
    }

    // Accessor untuk display name
    public function getDisplayNameAttribute()
    {
        return $this->diameter_pipa . ' (' . $this->kode_danameter . ')';
    }
}
