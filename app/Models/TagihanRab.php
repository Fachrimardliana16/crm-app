<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TagihanRab extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'tagihan_rab';
    protected $primaryKey = 'id_tagihan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_rab',
        'id_pelanggan',
        'nomor_tagihan',
        'tanggal_terbit',
        'jatuh_tempo',
        'total_tertagih',
        'status_pembayaran',
        'catatan_tagihan',
        'dibuat_oleh',
        'dibuat_pada',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'jatuh_tempo' => 'date',
        'total_tertagih' => 'decimal:2',
        'dibuat_pada' => 'datetime',
    ];

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_pembayaran',
                'total_tertagih',
                'jatuh_tempo',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class, 'id_rab', 'id_rab');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_tagihan', 'id_tagihan');
    }

    public function rincianAngsuran(): HasMany
    {
        return $this->hasMany(RincianAngsuran::class, 'id_tagihan', 'id_tagihan');
    }

    // Scopes
    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', '!=', 'lunas');
    }

    public function scopeJatuhTempo($query)
    {
        return $query->where('jatuh_tempo', '<=', now());
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'lunas' => 'success',
            'sebagian' => 'warning',
            'belum' => 'danger',
        ];

        return [
            'label' => ucfirst($this->status_pembayaran),
            'color' => $colors[$this->status_pembayaran] ?? 'gray',
        ];
    }

    public function getIsOverdueAttribute()
    {
        return $this->jatuh_tempo < now() && $this->status_pembayaran !== 'lunas';
    }

    public function getSisaTagihanAttribute()
    {
        $totalBayar = $this->pembayaran()->where('status_verifikasi', 'valid')->sum('jumlah_bayar');
        return $this->total_tertagih - $totalBayar;
    }
}
