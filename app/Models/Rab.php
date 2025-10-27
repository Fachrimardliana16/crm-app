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
        'id_pelanggan', // nullable - will be filled after installation complete
        // New fields
        'jenis_biaya_sambungan',
        // id_sub_rayon and no_langganan removed - will be assigned later
        'golongan_tarif',
        'tanggal_input',
        'nama_pelanggan',
        'alamat_pelanggan',
        'telepon_pelanggan',
        'kantor_cabang',
        // Rincian Uang Muka
        'perencanaan',
        'jumlah_uang_muka',
        // Biaya Instalasi
        'pengerjaan_tanah',
        'tenaga_kerja',
        'pipa_accessories',
        'jumlah_instalasi',
        // Rincian Piutang
        'pembulatan_piutang',
        'piutang_na',
        'total_piutang',
        'pajak_piutang',
        'total_biaya_sambungan_baru',
        // Existing fields
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
        'tanggal_input' => 'date',
        // New decimal fields
        'perencanaan' => 'decimal:2',
        'jumlah_uang_muka' => 'decimal:2',
        'pengerjaan_tanah' => 'decimal:2',
        'tenaga_kerja' => 'decimal:2',
        'pipa_accessories' => 'decimal:2',
        'jumlah_instalasi' => 'decimal:2',
        'pembulatan_piutang' => 'decimal:2',
        'piutang_na' => 'decimal:2',
        'total_piutang' => 'decimal:2',
        'pajak_piutang' => 'decimal:2',
        'total_biaya_sambungan_baru' => 'decimal:2',
        // Existing fields
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

    // subRayon relationship removed - will be assigned later after installation

    public function tagihanRab(): HasMany
    {
        return $this->hasMany(TagihanRab::class, 'id_rab', 'id_rab');
    }
}
