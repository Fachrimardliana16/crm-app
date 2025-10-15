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
        'tarif_dasar',
        'tarif_per_m3',
        'batas_minimum_m3',
        'tarif_progresif_1',
        'tarif_progresif_2',
        'tarif_progresif_3',
        'biaya_beban_tetap',
        'biaya_administrasi',
        'biaya_pemeliharaan',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'tarif_dasar' => 'decimal:2',
        'tarif_per_m3' => 'decimal:2',
        'tarif_progresif_1' => 'decimal:2',
        'tarif_progresif_2' => 'decimal:2',
        'tarif_progresif_3' => 'decimal:2',
        'biaya_beban_tetap' => 'decimal:2',
        'biaya_administrasi' => 'decimal:2',
        'biaya_pemeliharaan' => 'decimal:2',
        'batas_minimum_m3' => 'integer',
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

    public function getFormattedTarifAttribute()
    {
        if ($this->tarif_dasar) {
            return 'Rp ' . number_format($this->tarif_dasar, 0, ',', '.');
        }
        return 'Rp ' . number_format($this->tarif_per_m3, 0, ',', '.') . '/m³';
    }

    // Helper Methods
    public function hitungTarif($pemakaian)
    {
        if ($this->tarif_dasar) {
            // Fixed tariff
            return $this->tarif_dasar;
        }

        // Per m3 tariff
        $minimum = max($pemakaian, $this->batas_minimum_m3);
        return $minimum * $this->tarif_per_m3;
    }
}
