<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pelanggan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // Using custom timestamp columns

    protected $fillable = [
        'nomor_pelanggan',
        'nama_pelanggan',
        'nik',
        'jenis_identitas',
        'nomor_identitas',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'nomor_hp',
        'nomor_telepon',
        'email',
        'status_pelanggan',
        'golongan',
        'tipe_pelanggan',
        'segment',
        'id_area',
        'id_rayon',
        'id_sub_rayon',
        'id_spam',
        'latitude',
        'longitude',
        'elevasi',
        'kode_gis',
        'status_gis',
        'tgl_validasi_gis',
        'validasi_gis_oleh',
        'keterangan_gis',
        'keterangan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
        'status_historis',
        'tanggal_nonaktif',
        'tanggal_arsip',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_validasi_gis' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'tanggal_nonaktif' => 'date',
        'tanggal_arsip' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'elevasi' => 'decimal:2',
    ];

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_historis', 'aktif');
    }

    public function scopeByArea($query, $areaId)
    {
        return $query->where('id_area', $areaId);
    }

    public function scopeWithValidGis($query)
    {
        return $query->where('status_gis', 'valid');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nomor_pelanggan',
                'nama_pelanggan',
                'status_pelanggan',
                'latitude',
                'longitude',
                'status_gis'
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Pelanggan has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Helper Methods
    public function getFullAddressAttribute()
    {
        return $this->alamat . ', ' . $this->kelurahan . ', ' . $this->kecamatan . ', ' . $this->kota;
    }

    public function isGisValid()
    {
        return $this->status_gis === 'valid';
    }

    // Relationships
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    public function rayon()
    {
        return $this->belongsTo(Rayon::class, 'id_rayon', 'id_rayon');
    }

    public function subRayon()
    {
        return $this->belongsTo(SubRayon::class, 'id_sub_rayon', 'id_sub_rayon');
    }

    public function spam()
    {
        return $this->belongsTo(Spam::class, 'id_spam', 'id_spam');
    }

    public function golonganPelanggan()
    {
        return $this->belongsTo(GolonganPelanggan::class, 'id_golongan_pelanggan', 'id_golongan_pelanggan');
    }

    public function statusPelanggan()
    {
        return $this->belongsTo(Status::class, 'status_pelanggan', 'kode_status')
                   ->where('tabel_referensi', 'pelanggan');
    }

    public function tagihanBulanan()
    {
        return $this->hasMany(TagihanBulanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Generate nomor pelanggan otomatis
     * Format: {kode_rayon}{last_2_digit_sub_rayon}{nomor_urut}
     * Example: 01020003 (rayon 01, sub rayon 02, urut 0003)
     */
    public static function generateNomorPelanggan($subRayonId): string
    {
        $subRayon = SubRayon::with('rayon')->find($subRayonId);

        if (!$subRayon) {
            throw new \Exception('Sub Rayon tidak ditemukan');
        }

        return $subRayon->getNextNomorPelanggan();
    }

    /**
     * Generate nomor pelanggan sederhana untuk auto-create
     * Format: YYYY.MM.XXXX (contoh: 2025.10.0001)
     */
    public static function generateSimpleNomorPelanggan(): string
    {
        $year = date('Y');
        $month = date('m');
        
        // Format: YYYY.MM.XXXX
        $prefix = $year . '.' . $month . '.';
        
        // Cari nomor terakhir dengan prefix yang sama
        $lastPelanggan = static::where('nomor_pelanggan', 'LIKE', $prefix . '%')
            ->orderBy('nomor_pelanggan', 'desc')
            ->first();
            
        if ($lastPelanggan) {
            // Ambil 4 digit terakhir dan increment
            $lastNumber = intval(substr($lastPelanggan->nomor_pelanggan, -4));
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada, mulai dari 1
            $newNumber = 1;
        }
        
        // Format dengan padding 4 digit
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted nomor pelanggan dengan pemisah
     */
    public function getFormattedNomorPelangganAttribute(): string
    {
        if (!$this->nomor_pelanggan || strlen($this->nomor_pelanggan) !== 8) {
            return $this->nomor_pelanggan;
        }

        $rayon = substr($this->nomor_pelanggan, 0, 2);
        $subRayon = substr($this->nomor_pelanggan, 2, 2);
        $urut = substr($this->nomor_pelanggan, 4, 4);

        return "{$rayon}-{$subRayon}-{$urut}";
    }

    /**
     * Get breakdown nomor pelanggan
     */
    public function getNomorPelangganBreakdownAttribute(): array
    {
        if (!$this->nomor_pelanggan || strlen($this->nomor_pelanggan) !== 8) {
            return [
                'rayon' => null,
                'sub_rayon' => null,
                'urut' => null,
            ];
        }

        return [
            'rayon' => substr($this->nomor_pelanggan, 0, 2),
            'sub_rayon' => substr($this->nomor_pelanggan, 2, 2),
            'urut' => substr($this->nomor_pelanggan, 4, 4),
        ];
    }

    /**
     * Scope: Filter by rayon
     */
    public function scopeByRayon($query, $rayonId)
    {
        return $query->where('id_rayon', $rayonId);
    }

    /**
     * Scope: Filter by sub rayon
     */
    public function scopeBySubRayon($query, $subRayonId)
    {
        return $query->where('id_sub_rayon', $subRayonId);
    }

    /**
     * Scope: Filter by status pelanggan
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pelanggan', $status);
    }

    /**
     * Get status pelanggan badge information
     */
    public function getStatusBadgeAttribute()
    {
        return Status::getStatusBadge('pelanggan', $this->status_pelanggan);
    }

    /**
     * Check if pelanggan is active
     */
    public function isActive(): bool
    {
        return $this->status_pelanggan === 'AKTIF';
    }

    /**
     * Check if pelanggan is new
     */
    public function isNew(): bool
    {
        return $this->status_pelanggan === 'BARU';
    }

    /**
     * Check if pelanggan is temporarily closed
     */
    public function isTempClosed(): bool
    {
        return $this->status_pelanggan === 'TUTUP_SEMENTARA';
    }

    /**
     * Check if pelanggan is permanently closed
     */
    public function isPermClosed(): bool
    {
        return $this->status_pelanggan === 'TUTUP_TETAP';
    }

    /**
     * Check if pelanggan installation is dismantled
     */
    public function isDismantled(): bool
    {
        return $this->status_pelanggan === 'BONGKAR';
    }

    /**
     * Get available status options
     */
    public static function getStatusOptions(): array
    {
        return Status::getStatusOptions('pelanggan');
    }
}
