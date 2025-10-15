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
        'lokasi_map',
        'master_luas_tanah_id',
        'master_luas_bangunan_id',
        'master_lokasi_bangunan_id',
        'master_dinding_bangunan_id',
        'master_lantai_bangunan_id',
        'master_atap_bangunan_id',
        'master_pagar_bangunan_id',
        'master_kondisi_jalan_id',
        'master_daya_listrik_id',
        'master_fungsi_rumah_id',
        'master_kepemilikan_kendaraan_id',
        'skor_total',
        'hasil_survei',
        'kategori_golongan',
    ];

    protected $casts = [
        'tanggal_survei' => 'date',
        'latitude_terverifikasi' => 'decimal:8',
        'longitude_terverifikasi' => 'decimal:8',
        'elevasi_terverifikasi_mdpl' => 'decimal:2',
        'jarak_pemasangan' => 'decimal:2',
        'nilai_survei' => 'integer',
        'lokasi_map' => 'array',
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
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    // Master Data Relations
    public function masterLuasTanah()
    {
        return $this->belongsTo(MasterLuasTanah::class);
    }

    public function masterLuasBangunan()
    {
        return $this->belongsTo(MasterLuasBangunan::class);
    }

    public function masterLokasiBangunan()
    {
        return $this->belongsTo(MasterLokasiBangunan::class);
    }

    public function masterDindingBangunan()
    {
        return $this->belongsTo(MasterDindingBangunan::class);
    }

    public function masterLantaiBangunan()
    {
        return $this->belongsTo(MasterLantaiBangunan::class);
    }

    public function masterAtapBangunan()
    {
        return $this->belongsTo(MasterAtapBangunan::class);
    }

    public function masterPagarBangunan()
    {
        return $this->belongsTo(MasterPagarBangunan::class);
    }

    public function masterKondisiJalan()
    {
        return $this->belongsTo(MasterKondisiJalan::class);
    }

    public function masterDayaListrik()
    {
        return $this->belongsTo(MasterDayaListrik::class);
    }

    public function masterFungsiRumah()
    {
        return $this->belongsTo(MasterFungsiRumah::class);
    }

    public function masterKepemilikanKendaraan()
    {
        return $this->belongsTo(MasterKepemilikanKendaraan::class);
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
