<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pengaduan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'nomor_pengaduan',
        'tanggal_pengaduan',
        'jam_pengaduan',
        'kategori_pengaduan',
        'jenis_pengaduan',
        'uraian_pengaduan',
        'image',
        'prioritas',
        'status_pengaduan',
        'tanggal_target_selesai',
        'tanggal_selesai',
        'tindak_lanjut',
        'solusi_diberikan',
        'nip_petugas_penanganan',
        'biaya_penanganan',
        'foto_kondisi_awal',
        'foto_kondisi_akhir',
        'tingkat_kepuasan',
        'feedback_pelanggan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_pengaduan' => 'date',
        'jam_pengaduan' => 'datetime',
        'tanggal_target_selesai' => 'date',
        'tanggal_selesai' => 'date',
        'biaya_penanganan' => 'decimal:2',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Relationships
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_pengaduan',
                'prioritas',
                'tindak_lanjut',
                'solusi_diberikan',
                'tingkat_kepuasan',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
