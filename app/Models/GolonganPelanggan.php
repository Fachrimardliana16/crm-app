<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GolonganPelanggan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'golongan_pelanggan';
    protected $primaryKey = 'id_golongan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_golongan',
        'nama_golongan',
        'deskripsi_golongan',
        'tarif_dasar',
        'batas_minimum',
        'batas_maksimum',
        'tarif_progresif_1',
        'tarif_progresif_2',
        'tarif_progresif_3',
        'biaya_beban_tetap',
        'biaya_administrasi',
        'biaya_pemeliharaan',
        'status_aktif',
        'berlaku_sejak',
        'berlaku_hingga',
        'keterangan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'berlaku_sejak' => 'date',
        'berlaku_hingga' => 'date',
        'tarif_dasar' => 'decimal:2',
        'batas_minimum' => 'integer',
        'batas_maksimum' => 'integer',
        'tarif_progresif_1' => 'decimal:2',
        'tarif_progresif_2' => 'decimal:2',
        'tarif_progresif_3' => 'decimal:2',
        'biaya_beban_tetap' => 'decimal:2',
        'biaya_administrasi' => 'decimal:2',
        'biaya_pemeliharaan' => 'decimal:2',
        'status_aktif' => 'boolean',
    ];

    // Activity logging configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_golongan',
                'tarif_dasar',
                'status_aktif',
                'berlaku_sejak',
                'berlaku_hingga',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function pelanggan(): HasMany
    {
        return $this->hasMany(Pelanggan::class, 'golongan', 'id_golongan');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeBerlaku($query)
    {
        $today = now()->toDateString();
        return $query->where('berlaku_sejak', '<=', $today)
                    ->where(function($q) use ($today) {
                        $q->whereNull('berlaku_hingga')
                          ->orWhere('berlaku_hingga', '>=', $today);
                    });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return [
            'label' => $this->status_aktif ? 'Aktif' : 'Non-Aktif',
            'color' => $this->status_aktif ? 'success' : 'danger',
        ];
    }

    public function getFormattedTarifAttribute()
    {
        return 'Rp ' . number_format($this->tarif_dasar, 0, ',', '.');
    }

    // Helper Methods
    public function hitungTarif($pemakaian)
    {
        $total = 0;

        // Biaya beban tetap
        $total += $this->biaya_beban_tetap;

        // Tarif progresif
        if ($pemakaian <= $this->batas_minimum) {
            $total += $pemakaian * $this->tarif_dasar;
        } else {
            $total += $this->batas_minimum * $this->tarif_dasar;
            $sisa = $pemakaian - $this->batas_minimum;

            if ($sisa > 0 && $this->tarif_progresif_1 > 0) {
                $blok1 = min($sisa, 10); // Asumsi blok kedua 10 m3
                $total += $blok1 * $this->tarif_progresif_1;
                $sisa -= $blok1;
            }

            if ($sisa > 0 && $this->tarif_progresif_2 > 0) {
                $blok2 = min($sisa, 20); // Asumsi blok ketiga 20 m3
                $total += $blok2 * $this->tarif_progresif_2;
                $sisa -= $blok2;
            }

            if ($sisa > 0 && $this->tarif_progresif_3 > 0) {
                $total += $sisa * $this->tarif_progresif_3;
            }
        }

        // Biaya tambahan
        $total += $this->biaya_administrasi;
        $total += $this->biaya_pemeliharaan;

        return $total;
    }
}
