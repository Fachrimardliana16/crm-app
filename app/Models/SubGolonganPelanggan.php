<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class SubGolonganPelanggan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sub_golongan_pelanggan';
    protected $primaryKey = 'id_sub_golongan_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_golongan_pelanggan',
        'kode_sub_golongan',
        'nama_sub_golongan',
        'deskripsi',
        'biaya_tetap_subgolongan',
        'tarif_blok_1',
        'tarif_blok_2',
        'tarif_blok_3',
        'tarif_blok_4',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'biaya_tetap_subgolongan' => 'decimal:2',
        'tarif_blok_1' => 'decimal:2',
        'tarif_blok_2' => 'decimal:2',
        'tarif_blok_3' => 'decimal:2',
        'tarif_blok_4' => 'decimal:2',
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Activity logging configuration
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly([
    //             'nama_sub_golongan',
    //             'tarif_dasar',
    //             'tarif_per_m3',
    //             'is_active',
    //         ])
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }

    // Relationships
    public function golonganPelanggan(): BelongsTo
    {
        return $this->belongsTo(GolonganPelanggan::class, 'id_golongan_pelanggan', 'id_golongan_pelanggan');
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

    public function scopeByGolongan($query, $golonganId)
    {
        return $query->where('id_golongan_pelanggan', $golonganId);
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
        return $this->kode_sub_golongan . ' - ' . $this->nama_sub_golongan;
    }

    public function getFormattedBiayaTetapAttribute()
    {
        return 'Rp ' . number_format((float) $this->biaya_tetap_subgolongan, 0, ',', '.');
    }

    // Helper Methods untuk perhitungan tarif progresif per 10 m³
    public function hitungTarifVolume($volume)
    {
        $totalTarif = 0;
        
        if ($volume <= 10) {
            // Blok 1: 0-10 m³
            $totalTarif = $this->tarif_blok_1;
        } elseif ($volume <= 20) {
            // Blok 1 + Blok 2: 0-10 m³ + 11-20 m³
            $totalTarif = $this->tarif_blok_1 + $this->tarif_blok_2;
        } elseif ($volume <= 30) {
            // Blok 1 + Blok 2 + Blok 3: 0-10 m³ + 11-20 m³ + 21-30 m³
            $totalTarif = $this->tarif_blok_1 + $this->tarif_blok_2 + $this->tarif_blok_3;
        } else {
            // Blok 1 + Blok 2 + Blok 3 + Blok 4: semua blok
            $totalTarif = $this->tarif_blok_1 + $this->tarif_blok_2 + $this->tarif_blok_3 + $this->tarif_blok_4;
        }
        
        return $totalTarif;
    }

    public function hitungTotalTarif($volume, $tarifDanameter = 0)
    {
        $biayaTetap = (float) $this->biaya_tetap_subgolongan;
        $biayaDanameter = (float) $tarifDanameter;
        $biayaVolume = $this->hitungTarifVolume($volume);
        
        return $biayaTetap + $biayaDanameter + $biayaVolume;
    }

    // Accessor untuk display tarif blok
    public function getTarifBlokDisplayAttribute()
    {
        return [
            'blok_1' => 'Rp ' . number_format((float) $this->tarif_blok_1, 0, ',', '.') . ' (0-10 m³)',
            'blok_2' => 'Rp ' . number_format((float) $this->tarif_blok_2, 0, ',', '.') . ' (11-20 m³)',
            'blok_3' => 'Rp ' . number_format((float) $this->tarif_blok_3, 0, ',', '.') . ' (21-30 m³)',
            'blok_4' => 'Rp ' . number_format((float) $this->tarif_blok_4, 0, ',', '.') . ' (>30 m³)',
        ];
    }
}
