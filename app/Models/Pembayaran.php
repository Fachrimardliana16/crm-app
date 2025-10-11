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
        'dibuat_oleh',
        'dibuat_pada',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
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
}
