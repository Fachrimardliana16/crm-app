<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nomor_registrasi',
        'id_pelanggan', // nullable - diisi setelah pendaftaran disetujui
        'id_cabang',
        'id_kelurahan',
        'id_pekerjaan',
        'id_tipe_layanan',
        'id_jenis_daftar',
        'id_tipe_pendaftaran',
        'jenis_identitas',
        'nomor_identitas',
        'tanggal_daftar',
        'nama_pemohon',
        'alamat_pemasangan',
        'no_hp_pemohon',
        'email_pemohon',
        'latitude_awal',
        'longitude_awal',
        'elevasi_awal_mdpl',
        'keterangan_arah_lokasi',
        'scan_identitas_utama',
        'scan_dokumen_mou',
        'jumlah_pemakai',
        'ada_toren',
        'ada_sumur',
        'biaya_tipe_layanan',
        'biaya_jenis_daftar',
        'biaya_tipe_pendaftaran',
        'biaya_tambahan',
        'subtotal_biaya',
        'id_pajak',
        'nilai_pajak',
        'total_biaya_pendaftaran',
        'status_pendaftaran', // Added missing field
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'latitude_awal' => 'decimal:8',
        'longitude_awal' => 'decimal:8',
        'elevasi_awal_mdpl' => 'decimal:2',
        'data_pengembalian' => 'decimal:2',
        'biaya_tipe_layanan' => 'decimal:2',
        'biaya_jenis_daftar' => 'decimal:2',
        'biaya_tipe_pendaftaran' => 'decimal:2',
        'biaya_tambahan' => 'decimal:2',
        'subtotal_biaya' => 'decimal:2',
        'nilai_pajak' => 'decimal:2',
        'total_biaya_pendaftaran' => 'decimal:2',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'ada_toren' => 'boolean',
        'ada_sumur' => 'boolean',
    ];

    protected $encrypted = [
        'nama_pemohon',
        'email_pemohon',
        'nomor_identitas',
        'scan_identitas_utama',
        'scan_dokumen_mou',
    ];

    protected $attributes = [
        'status_pendaftaran' => 'draft',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->status_pendaftaran)) {
                $model->status_pendaftaran = 'draft';
            }
        });
    }

    // Relationships
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Pekerjaan::class, 'id_pekerjaan', 'id_pekerjaan');
    }

    public function tipeLayanan(): BelongsTo
    {
        return $this->belongsTo(TipeLayanan::class, 'id_tipe_layanan', 'id_tipe_layanan');
    }

    public function jenisDaftar(): BelongsTo
    {
        return $this->belongsTo(JenisDaftar::class, 'id_jenis_daftar', 'id_jenis_daftar');
    }

    public function tipePendaftaran(): BelongsTo
    {
        return $this->belongsTo(TipePendaftaran::class, 'id_tipe_pendaftaran', 'id_tipe_pendaftaran');
    }

    public function survei(): HasOne
    {
        return $this->hasOne(Survei::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function rab(): HasOne
    {
        return $this->hasOne(Rab::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function instalasi(): HasOne
    {
        return $this->hasOne(Instalasi::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function pajak(): BelongsTo
    {
        return $this->belongsTo(Pajak::class, 'id_pajak', 'id_pajak');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pendaftaran', $status);
    }

    public function scopeByJenisDaftar($query, $jenis)
    {
        return $query->where('jenis_daftar', $jenis);
    }

    public function scopeTerbaru($query)
    {
        return $query->orderBy('tanggal_daftar', 'desc');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'disetujui' => 'success',
            'ditolak' => 'danger',
            'survei' => 'warning',
            'rab' => 'info',
            'instalasi' => 'primary',
            'selesai' => 'success',
        ];

        return [
            'label' => ucfirst($this->status_pendaftaran),
            'color' => $colors[$this->status_pendaftaran] ?? 'gray',
        ];
    }

    public function getCoordinatesAttribute()
    {
        if ($this->latitude_awal && $this->longitude_awal) {
            return [
                'lat' => (float) $this->latitude_awal,
                'lng' => (float) $this->longitude_awal,
                'elevation' => (float) $this->elevasi_awal_mdpl,
            ];
        }
        return null;
    }

    public function getKecamatanIdAttribute()
    {
        return $this->kelurahan?->id_kecamatan;
    }

    // Setter untuk geom
    public function setGeomAttribute($value)
    {
        if ($this->latitude_awal && $this->longitude_awal) {
            $this->attributes['geom'] = DB::raw("ST_SetSRID(ST_MakePoint({$this->longitude_awal}, {$this->latitude_awal}), 4326)");
        } else {
            $this->attributes['geom'] = null;
        }
    }
}
