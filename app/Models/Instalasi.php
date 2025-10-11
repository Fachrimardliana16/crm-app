<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Instalasi extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'instalasi';
    protected $primaryKey = 'id_instalasi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pendaftaran',
        'id_pelanggan',
        'nip_teknisi',
        'tanggal_instalasi',
        'jam_mulai',
        'jam_selesai',
        'status_instalasi',
        'nomor_meter',
        'merk_meter',
        'posisi_meter_latitude',
        'posisi_meter_longitude',
        'elevasi_meter_mdpl',
        'tekanan_air',
        'jenis_pipa',
        'diameter_pipa',
        'foto_meter_terpasang',
        'foto_instalasi_pipa',
        'foto_hasil_instalasi',
        'catatan_instalasi',
        'kendala_teknis',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_instalasi' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'posisi_meter_latitude' => 'decimal:8',
        'posisi_meter_longitude' => 'decimal:8',
        'elevasi_meter_mdpl' => 'decimal:2',
        'tekanan_air' => 'decimal:2',
        'diameter_pipa' => 'decimal:2',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_instalasi',
                'nomor_meter',
                'posisi_meter_latitude',
                'posisi_meter_longitude',
                'tanggal_instalasi',
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

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_instalasi', $status);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_instalasi', 'selesai');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'terjadwal' => 'secondary',
            'progres' => 'warning',
            'selesai' => 'success',
            'ditunda' => 'danger',
        ];

        return [
            'label' => ucfirst($this->status_instalasi),
            'color' => $colors[$this->status_instalasi] ?? 'gray',
        ];
    }
}
