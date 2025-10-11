<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipePendaftaran extends Model
{
    use HasFactory, HasUuids; // , LogsActivity - disabled temporarily

    protected $table = 'tipe_pendaftaran';
    protected $primaryKey = 'id_tipe_pendaftaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_tipe_pendaftaran',
        'nama_tipe_pendaftaran',
        'deskripsi',
        'biaya_admin',
        'prioritas',
        'perlu_survei',
        'otomatis_approve',
        'status_aktif',
    ];

    protected $casts = [
        'biaya_admin' => 'decimal:2',
        'prioritas' => 'integer',
        'perlu_survei' => 'boolean',
        'otomatis_approve' => 'boolean',
        'status_aktif' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_tipe_pendaftaran', 'biaya', 'data_pengembalian', 'status_aktif'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_tipe_pendaftaran', 'id_tipe_pendaftaran');
    }
}
