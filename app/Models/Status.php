<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Status extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'status';
    protected $primaryKey = 'id_status';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'tabel_referensi',
        'kode_status',
        'nama_status',
        'deskripsi_status',
        'warna_status',
        'urutan_tampil',
        'status_aktif',
        'keterangan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'status_aktif' => 'boolean',
        'urutan_tampil' => 'integer',
    ];

    // Activity logging configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'tabel_referensi',
                'nama_status',
                'status_aktif',
                'urutan_tampil',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeByTabel($query, $tabel)
    {
        return $query->where('tabel_referensi', $tabel);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan_tampil');
    }

    // Static methods untuk mendapatkan status berdasarkan tabel
    public static function getStatusOptions($tabel)
    {
        return static::byTabel($tabel)
                    ->aktif()
                    ->ordered()
                    ->pluck('nama_status', 'kode_status')
                    ->toArray();
    }

    public static function getStatusBadge($tabel, $kode)
    {
        $status = static::byTabel($tabel)
                       ->where('kode_status', $kode)
                       ->first();

        if (!$status) {
            return [
                'label' => ucfirst($kode),
                'color' => 'gray',
            ];
        }

        return [
            'label' => $status->nama_status,
            'color' => $status->warna_status ?? 'gray',
        ];
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return [
            'label' => $this->status_aktif ? 'Aktif' : 'Non-Aktif',
            'color' => $this->status_aktif ? 'success' : 'danger',
        ];
    }
}
