<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pembayaran extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_tagihan',
        'id_pelanggan',
        'nomor_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'biaya_admin',
        'bukti_bayar',
        'metode_bayar',
        'nip_petugas_loket',
        'status_verifikasi',
        'catatan_pembayaran',
        'jenis_pembayaran', // rekening, pendaftaran, lainnya
        'uang_diterima', // untuk cash
        'kembalian', // untuk cash
        'total_tagihan', // total yang harus dibayar
        'sisa_tagihan', // sisa yang belum dibayar
        'periode_pembayaran', // periode tagihan
        'dibuat_oleh',
        'dibuat_pada',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
        'uang_diterima' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'total_tagihan' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
        'dibuat_pada' => 'datetime',
    ];

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_verifikasi',
                'jumlah_bayar',
                'metode_bayar',
                'tanggal_bayar',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(TagihanRab::class, 'id_tagihan', 'id_tagihan');
    }

    public function tagihanBulanan(): BelongsTo
    {
        return $this->belongsTo(TagihanBulanan::class, 'id_tagihan', 'id_tagihan_bulanan');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'valid');
    }

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('metode_bayar', $method);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pembayaran', $jenis);
    }

    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode_pembayaran', $periode);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_verifikasi', 'valid')
                    ->whereColumn('jumlah_bayar', '>=', 'total_tagihan');
    }

    public function scopeSebagian($query)
    {
        return $query->where('status_verifikasi', 'valid')
                    ->whereColumn('jumlah_bayar', '<', 'total_tagihan');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'valid' => 'success',
            'tidak_valid' => 'danger',
            'pending' => 'warning',
        ];

        return [
            'label' => ucfirst(str_replace('_', ' ', $this->status_verifikasi)),
            'color' => $colors[$this->status_verifikasi] ?? 'gray',
        ];
    }

    public function getTotalBayarAttribute()
    {
        return $this->jumlah_bayar + $this->biaya_admin;
    }

    // Additional methods for pembayaran page
    public function getJenisBadgeAttribute()
    {
        $colors = [
            'rekening' => 'primary',
            'pendaftaran' => 'success',
            'lainnya' => 'info',
        ];

        return [
            'label' => ucfirst($this->jenis_pembayaran ?? 'rekening'),
            'color' => $colors[$this->jenis_pembayaran ?? 'rekening'] ?? 'gray',
        ];
    }

    public function getMetodeBadgeAttribute()
    {
        $colors = [
            'cash' => 'success',
            'qris' => 'primary',
            'debit' => 'warning',
            'credit' => 'info',
        ];

        return [
            'label' => strtoupper($this->metode_bayar),
            'color' => $colors[$this->metode_bayar] ?? 'gray',
        ];
    }

    public function isLunas(): bool
    {
        return $this->status_verifikasi === 'valid' &&
               $this->jumlah_bayar >= ($this->total_tagihan ?? $this->jumlah_bayar);
    }

    public function isSebagian(): bool
    {
        return $this->status_verifikasi === 'valid' &&
               $this->jumlah_bayar < ($this->total_tagihan ?? $this->jumlah_bayar);
    }

    public function hitungKembalian(): float
    {
        if ($this->metode_bayar === 'cash' && $this->uang_diterima) {
            return max(0, $this->uang_diterima - $this->jumlah_bayar);
        }
        return 0;
    }

    // Generate nomor pembayaran
    public static function generateNomorPembayaran(): string
    {
        $date = now()->format('Ymd');
        $lastNumber = static::whereDate('dibuat_pada', now())
            ->orderBy('dibuat_pada', 'desc')
            ->first();

        if ($lastNumber && $lastNumber->nomor_pembayaran) {
            // Extract last 4 digits from existing format
            $lastNumberPart = substr($lastNumber->nomor_pembayaran, -4);
            $nextNumber = str_pad((int)$lastNumberPart + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return 'PAY' . $date . $nextNumber;
    }

    // Get available jenis pembayaran options
    public static function getJenisPembayaranOptions(): array
    {
        return [
            'rekening' => 'Pembayaran Rekening Air',
            'pendaftaran' => 'Pembayaran Pendaftaran',
            'lainnya' => 'Pembayaran Lainnya',
        ];
    }

    // Get available metode pembayaran options
    public static function getMetodePembayaranOptions(): array
    {
        return [
            'cash' => 'Cash/Tunai',
            'qris' => 'QRIS',
            'debit' => 'Kartu Debit',
            'credit' => 'Kartu Kredit',
        ];
    }
}
