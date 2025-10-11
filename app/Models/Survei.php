<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Survei extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'survei';
    protected $primaryKey = 'id_survei';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pendaftaran',
        'id_pelanggan',
        'id_spam',
        'nip_surveyor',
        'tanggal_survei',
        'status_survei',
        'subrayon',
        'latitude_terverifikasi',
        'longitude_terverifikasi',
        'elevasi_terverifikasi_mdpl',
        'jarak_pemasangan',
        'catatan_teknis',
        'nilai_survei',
        'golongan_survei',
        'kelas_survei_input',
        'foto_peta_lokasi',
        'foto_tanah_bangunan',
        'foto_dinding',
        'foto_lantai',
        'foto_atap',
        'foto_pagar',
        'foto_jalan',
        'foto_meteran_listrik',
        'rekomendasi_teknis',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_survei' => 'date',
        'latitude_terverifikasi' => 'decimal:8',
        'longitude_terverifikasi' => 'decimal:8',
        'elevasi_terverifikasi_mdpl' => 'decimal:2',
        'jarak_pemasangan' => 'decimal:2',
        'nilai_survei' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Activity logging configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_survei',
                'nilai_survei',
                'rekomendasi_teknis',
                'latitude_terverifikasi',
                'longitude_terverifikasi',
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

    public function spam(): BelongsTo
    {
        return $this->belongsTo(Spam::class, 'id_spam', 'id_spam');
    }
}
