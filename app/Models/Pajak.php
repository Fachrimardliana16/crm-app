<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pajak extends Model
{
    use HasFactory, HasUuids; // , LogsActivity - disabled temporarily

    protected $table = 'pajak';
    protected $primaryKey = 'id_pajak';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_pajak',
        'nama_pajak',
        'deskripsi',
        'persentase_pajak',
        'nilai_tetap',
        'jenis_pajak',
        'status_aktif',
    ];

    protected $casts = [
        'persentase_pajak' => 'decimal:2',
        'nilai_tetap' => 'decimal:2',
        'status_aktif' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_pajak', 'persentase_pajak', 'nilai_tetap', 'status_aktif'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Helper method untuk menghitung nilai pajak
    public function hitungPajak($nilaiDasar)
    {
        if ($this->jenis_pajak === 'persentase') {
            return $nilaiDasar * ($this->persentase_pajak / 100);
        } else {
            return $this->nilai_tetap;
        }
    }

    // Relationships
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pajak', 'id_pajak');
    }
}
