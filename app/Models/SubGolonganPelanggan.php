<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rupadana\ApiService\Contracts\HasAllowedFields;
use Rupadana\ApiService\Contracts\HasAllowedFilters;
use Rupadana\ApiService\Contracts\HasAllowedSorts;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class SubGolonganPelanggan extends Model implements HasAllowedFields, HasAllowedFilters, HasAllowedSorts
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
        $remainingVolume = $volume;
        
        // Blok 1: 0-10 m³
        if ($remainingVolume > 0) {
            $volumeBlok1 = min($remainingVolume, 10);
            $totalTarif += $volumeBlok1 * $this->tarif_blok_1;
            $remainingVolume -= $volumeBlok1;
        }
        
        // Blok 2: 11-20 m³
        if ($remainingVolume > 0) {
            $volumeBlok2 = min($remainingVolume, 10);
            $totalTarif += $volumeBlok2 * $this->tarif_blok_2;
            $remainingVolume -= $volumeBlok2;
        }
        
        // Blok 3: 21-30 m³
        if ($remainingVolume > 0) {
            $volumeBlok3 = min($remainingVolume, 10);
            $totalTarif += $volumeBlok3 * $this->tarif_blok_3;
            $remainingVolume -= $volumeBlok3;
        }
        
        // Blok 4: >30 m³
        if ($remainingVolume > 0) {
            $totalTarif += $remainingVolume * $this->tarif_blok_4;
        }
        
        return $totalTarif;
    }

    public function hitungTotalTarif($volume, $tarifDanameter = 0)
    {
        $biayaTetap = (float) $this->biaya_tetap_subgolongan;
        $biayaDanameter = (float) $tarifDanameter;
        $biayaVolume = $this->hitungTarifVolume($volume);
        
        return [
            'biaya_tetap' => $biayaTetap,
            'biaya_pemakaian' => $biayaVolume,
            'biaya_danameter' => $biayaDanameter,
            'total_tarif' => $biayaTetap + $biayaDanameter + $biayaVolume,
            'volume_m3' => $volume,
            'detail_blok' => $this->getDetailBlok($volume)
        ];
    }

    /**
     * Get detailed calculation per block
     */
    public function getDetailBlok($volume)
    {
        $details = [];
        $remainingVolume = $volume;

        // Blok 1: 0-10 m³
        if ($remainingVolume > 0) {
            $volumeBlok1 = min($remainingVolume, 10);
            $details[] = [
                'blok' => 1,
                'range' => '0-10 m³',
                'volume_terpakai' => $volumeBlok1,
                'tarif_per_m3' => $this->tarif_blok_1,
                'subtotal' => $volumeBlok1 * $this->tarif_blok_1
            ];
            $remainingVolume -= $volumeBlok1;
        }

        // Blok 2: 11-20 m³
        if ($remainingVolume > 0) {
            $volumeBlok2 = min($remainingVolume, 10);
            $details[] = [
                'blok' => 2,
                'range' => '11-20 m³',
                'volume_terpakai' => $volumeBlok2,
                'tarif_per_m3' => $this->tarif_blok_2,
                'subtotal' => $volumeBlok2 * $this->tarif_blok_2
            ];
            $remainingVolume -= $volumeBlok2;
        }

        // Blok 3: 21-30 m³
        if ($remainingVolume > 0) {
            $volumeBlok3 = min($remainingVolume, 10);
            $details[] = [
                'blok' => 3,
                'range' => '21-30 m³',
                'volume_terpakai' => $volumeBlok3,
                'tarif_per_m3' => $this->tarif_blok_3,
                'subtotal' => $volumeBlok3 * $this->tarif_blok_3
            ];
            $remainingVolume -= $volumeBlok3;
        }

        // Blok 4: >30 m³
        if ($remainingVolume > 0) {
            $details[] = [
                'blok' => 4,
                'range' => '>30 m³',
                'volume_terpakai' => $remainingVolume,
                'tarif_per_m3' => $this->tarif_blok_4,
                'subtotal' => $remainingVolume * $this->tarif_blok_4
            ];
        }

        return $details;
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

    // API Service Contracts Implementation
    public static function getAllowedFields(): array
    {
        return [
            'id_sub_golongan_pelanggan',
            'id_golongan_pelanggan',
            'kode_sub_golongan',
            'nama_sub_golongan',
            'deskripsi',
            'tarif_dasar',
            'tarif_blok_1',
            'tarif_blok_2',
            'tarif_blok_3',
            'tarif_blok_4',
            'beban_tetap',
            'is_active',
            'urutan',
            'created_at',
            'updated_at',
        ];
    }

    public static function getAllowedFilters(): array
    {
        return [
            'id_golongan_pelanggan',
            'kode_sub_golongan',
            'nama_sub_golongan',
            'is_active',
            'urutan',
        ];
    }

    public static function getAllowedSorts(): array
    {
        return [
            'kode_sub_golongan',
            'nama_sub_golongan',
            'tarif_dasar',
            'urutan',
            'created_at',
            'updated_at',
        ];
    }
}