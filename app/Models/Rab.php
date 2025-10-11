<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Rab extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'rab';
    protected $primaryKey = 'id_rab';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pendaftaran',
        'id_pelanggan',
        'tanggal_rab_dibuat',
        'status_rab',
        'total_biaya_konstruksi',
        'total_biaya_administrasi',
        'sub_total_awal',
        'nilai_pajak',
        'total_rab_bruto',
        'pembulatan',
        'total_final_rab',
        'uang_muka',
        'biaya_sb',
        'piutang_non_adir',
        'jumlah_angsuran',
        'status_pembayaran',
        'catatan_rab',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_rab_dibuat' => 'date',
        'total_biaya_konstruksi' => 'decimal:2',
        'total_biaya_administrasi' => 'decimal:2',
        'sub_total_awal' => 'decimal:2',
        'nilai_pajak' => 'decimal:2',
        'total_rab_bruto' => 'decimal:2',
        'pembulatan' => 'decimal:2',
        'total_final_rab' => 'decimal:2',
        'uang_muka' => 'decimal:2',
        'biaya_sb' => 'decimal:2',
        'piutang_non_adir' => 'decimal:2',
        'jumlah_angsuran' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_rab',
                'total_final_rab',
                'status_pembayaran',
                'jumlah_angsuran',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function tagihanRab(): HasMany
    {
        return $this->hasMany(TagihanRab::class, 'id_rab', 'id_rab');
    }
}
