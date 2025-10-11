<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RincianAngsuran extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'rincian_angsuran';
    protected $primaryKey = 'id_angsuran';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_tagihan',
        'nomor_angsuran',
        'jumlah_angsuran',
        'tanggal_jatuh_tempo',
        'status_bayar',
        'tanggal_bayar',
        'denda',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_angsuran' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'denda' => 'decimal:2',
    ];

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_bayar',
                'tanggal_bayar',
                'denda',
                'jumlah_angsuran',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(TagihanRab::class, 'id_tagihan', 'id_tagihan');
    }

    // Scopes
    public function scopeLunas($query)
    {
        return $query->where('status_bayar', 'lunas');
    }

    public function scopeBelumBayar($query)
    {
        return $query->where('status_bayar', 'belum');
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status_bayar', 'terlambat');
    }

    public function scopeJatuhTempo($query)
    {
        return $query->where('tanggal_jatuh_tempo', '<=', now());
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'lunas' => 'success',
            'belum' => 'warning',
            'terlambat' => 'danger',
        ];

        return [
            'label' => ucfirst($this->status_bayar),
            'color' => $colors[$this->status_bayar] ?? 'gray',
        ];
    }

    public function getIsOverdueAttribute()
    {
        return $this->tanggal_jatuh_tempo < now() && $this->status_bayar !== 'lunas';
    }

    public function getTotalBayarAttribute()
    {
        return $this->jumlah_angsuran + $this->denda;
    }
}
