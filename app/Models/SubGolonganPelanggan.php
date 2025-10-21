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
        // PDAM Purbalingga block system
        'biaya_tetap_subgolongan',
        'tarif_blok_1',
        'tarif_blok_2',
        'tarif_blok_3',
        'tarif_blok_4',
        // Alternative tariff structure
        'tarif_dasar',
        'tarif_per_m3',
        'batas_minimum_m3',
        // Additional costs
        'biaya_beban_tetap',
        'biaya_administrasi',
        'biaya_pemeliharaan',
        'is_active',
        'urutan',
        // Scoring system
        'skor_minimum',
        'skor_maksimum',
        'kriteria_scoring',
        'gunakan_scoring',
        'prioritas_scoring',
    ];

    protected $casts = [
        // PDAM Purbalingga block system
        'biaya_tetap_subgolongan' => 'decimal:2',
        'tarif_blok_1' => 'decimal:2',
        'tarif_blok_2' => 'decimal:2',
        'tarif_blok_3' => 'decimal:2',
        'tarif_blok_4' => 'decimal:2',
        // Alternative tariff structure
        'tarif_dasar' => 'decimal:2',
        'tarif_per_m3' => 'decimal:2',
        'batas_minimum_m3' => 'integer',
        // Additional costs
        'biaya_beban_tetap' => 'decimal:2',
        'biaya_administrasi' => 'decimal:2',
        'biaya_pemeliharaan' => 'decimal:2',
        'is_active' => 'boolean',
        'urutan' => 'integer',
        // Scoring system
        'skor_minimum' => 'integer',
        'skor_maksimum' => 'integer',
        'gunakan_scoring' => 'boolean',
        'prioritas_scoring' => 'integer',
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

    public function scopeByScoring($query, $skor)
    {
        return $query->where('gunakan_scoring', true)
                    ->where('skor_minimum', '<=', $skor)
                    ->where(function ($q) use ($skor) {
                        $q->whereNull('skor_maksimum')
                          ->orWhere('skor_maksimum', '>=', $skor);
                    });
    }

    public function scopePrioritasScoring($query)
    {
        return $query->orderBy('prioritas_scoring', 'desc');
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

    // Accessor untuk display scoring range
    public function getScoringRangeDisplayAttribute()
    {
        if (!$this->gunakan_scoring) {
            return 'Tidak menggunakan scoring otomatis';
        }

        $min = $this->skor_minimum;
        $max = $this->skor_maksimum;

        if ($max === null) {
            return "≥ {$min} poin";
        }

        return "{$min} - {$max} poin";
    }

    // Method untuk mengecek apakah skor masuk dalam range sub golongan ini
    public function isScoreInRange($skor)
    {
        if (!$this->gunakan_scoring) {
            return false;
        }

        if ($skor < $this->skor_minimum) {
            return false;
        }

        if ($this->skor_maksimum !== null && $skor > $this->skor_maksimum) {
            return false;
        }

        return true;
    }

    // Static method untuk menentukan sub golongan berdasarkan skor
    public static function tentukanSubGolonganBySkor($skor, $golonganId = null)
    {
        $query = self::aktif()
                    ->where('gunakan_scoring', true)
                    ->byScoring($skor)
                    ->prioritasScoring();

        if ($golonganId) {
            $query->byGolongan($golonganId);
        }

        return $query->first();
    }

    // Static method untuk mendapatkan semua sub golongan yang cocok dengan skor
    public static function getSubGolonganBySkor($skor, $golonganId = null)
    {
        $query = self::aktif()
                    ->where('gunakan_scoring', true)
                    ->byScoring($skor)
                    ->prioritasScoring();

        if ($golonganId) {
            $query->byGolongan($golonganId);
        }

        return $query->get();
    }

    // Method untuk mendapatkan rekomendasi sub golongan berdasarkan parameter survei
    public static function rekomendasiSubGolongan($skorTotal, $golonganId = null)
    {
        $subGolongan = self::tentukanSubGolonganBySkor($skorTotal, $golonganId);
        
        if (!$subGolongan) {
            // Jika tidak ada yang cocok, ambil sub golongan dengan skor minimum terdekat
            $query = self::aktif()->where('gunakan_scoring', true);
            
            if ($golonganId) {
                $query->byGolongan($golonganId);
            }
            
            $subGolongan = $query->orderBy('skor_minimum', 'desc')
                               ->where('skor_minimum', '<=', $skorTotal)
                               ->first();
        }

        return $subGolongan;
    }
}
