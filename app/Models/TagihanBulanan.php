<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TagihanBulanan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'tagihan_bulanan';
    protected $primaryKey = 'id_tagihan_bulanan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'id_bacaan',
        'periode_tagihan',
        'tanggal_terbit',
        'jatuh_tempo',
        'pemakaian_air',
        'tarif_dasar',
        'biaya_pemakaian',
        'biaya_beban',
        'biaya_administrasi',
        'biaya_pemeliharaan',
        'biaya_meter',
        'biaya_denda',
        'total_tagihan',
        'status_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'dibuat_oleh',
        'dibuat_pada',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'pemakaian_air' => 'integer',
        'tarif_dasar' => 'decimal:2',
        'biaya_pemakaian' => 'decimal:2',
        'biaya_beban' => 'decimal:2',
        'biaya_administrasi' => 'decimal:2',
        'biaya_pemeliharaan' => 'decimal:2',
        'biaya_meter' => 'decimal:2',
        'biaya_denda' => 'decimal:2',
        'total_tagihan' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'dibuat_pada' => 'datetime',
    ];

    // Relationships
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function bacaanMeter(): BelongsTo
    {
        return $this->belongsTo(BacaanMeter::class, 'id_bacaan', 'id_bacaan');
    }

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_pembayaran',
                'total_tagihan',
                'tanggal_bayar',
                'jumlah_bayar',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
