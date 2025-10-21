<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class Survei extends Model
{
    use HasFactory, HasUuids; // LogsActivity temporarily removed

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
        'rekomendasi_sub_golongan_id',
        'rekomendasi_sub_golongan_text',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->dibuat_oleh)) {
                $model->dibuat_oleh = auth()->user()->name ?? auth()->user()->email ?? 'System';
            }
            if (empty($model->dibuat_pada)) {
                $model->dibuat_pada = now();
            }
        });

        static::updating(function ($model) {
            $model->diperbarui_oleh = auth()->user()->name ?? auth()->user()->email ?? 'System';
            $model->diperbarui_pada = now();
        });
    }

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

    // Activity logging temporarily disabled due to UUID compatibility issue with bigint primary keys
    // Will need to configure activity_log table to support UUID primary keys or use different logging approach

    // Relationships
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
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

    public function rekomendasiSubGolongan(): BelongsTo
    {
        return $this->belongsTo(SubGolonganPelanggan::class, 'rekomendasi_sub_golongan_id', 'id_sub_golongan_pelanggan');
    }

    // Method untuk menghitung total skor survei
    public function hitungTotalSkor()
    {
        $totalSkor = 0;

        // Skor dari master luas tanah
        if ($this->masterLuasTanah) {
            $totalSkor += $this->masterLuasTanah->skor;
        }

        // Skor dari master luas bangunan
        if ($this->masterLuasBangunan) {
            $totalSkor += $this->masterLuasBangunan->skor;
        }

        // Skor dari master lokasi bangunan
        if ($this->masterLokasiBangunan) {
            $totalSkor += $this->masterLokasiBangunan->skor;
        }

        // Skor dari master dinding bangunan
        if ($this->masterDindingBangunan) {
            $totalSkor += $this->masterDindingBangunan->skor;
        }

        // Skor dari master lantai bangunan
        if ($this->masterLantaiBangunan) {
            $totalSkor += $this->masterLantaiBangunan->skor;
        }

        // Skor dari master atap bangunan
        if ($this->masterAtapBangunan) {
            $totalSkor += $this->masterAtapBangunan->skor;
        }

        // Skor dari master pagar bangunan
        if ($this->masterPagarBangunan) {
            $totalSkor += $this->masterPagarBangunan->skor;
        }

        // Skor dari master kondisi jalan
        if ($this->masterKondisiJalan) {
            $totalSkor += $this->masterKondisiJalan->skor;
        }

        // Skor dari master daya listrik
        if ($this->masterDayaListrik) {
            $totalSkor += $this->masterDayaListrik->skor;
        }

        // Skor dari master fungsi rumah
        if ($this->masterFungsiRumah) {
            $totalSkor += $this->masterFungsiRumah->skor;
        }

        // Skor dari master kepemilikan kendaraan
        if ($this->masterKepemilikanKendaraan) {
            $totalSkor += $this->masterKepemilikanKendaraan->skor;
        }

        return $totalSkor;
    }

    // Method untuk menentukan sub golongan berdasarkan skor total
    public function tentukanSubGolongan($golonganId = null)
    {
        $skorTotal = $this->hitungTotalSkor();
        
        // Update skor total di record survei
        $this->update(['skor_total' => $skorTotal]);

        // Cari sub golongan yang sesuai
        $subGolongan = SubGolonganPelanggan::rekomendasiSubGolongan($skorTotal, $golonganId);
        
        // Update rekomendasi di database
        if ($subGolongan) {
            $this->update([
                'rekomendasi_sub_golongan_id' => $subGolongan->id_sub_golongan_pelanggan,
                'rekomendasi_sub_golongan_text' => $subGolongan->nama_sub_golongan . ' (' . $subGolongan->scoring_range_display . ')',
            ]);
        } else {
            $this->update([
                'rekomendasi_sub_golongan_id' => null,
                'rekomendasi_sub_golongan_text' => 'Tidak ada sub golongan yang sesuai dengan skor ' . $skorTotal,
            ]);
        }

        return $subGolongan;
    }

    // Method untuk mendapatkan rekomendasi sub golongan dengan detail
    public function getRekomendasiSubGolongan($golonganId = null)
    {
        $skorTotal = $this->hitungTotalSkor();
        $subGolongan = $this->tentukanSubGolongan($golonganId);

        return [
            'skor_total' => $skorTotal,
            'sub_golongan' => $subGolongan,
            'kategori_golongan' => $subGolongan ? $this->kategoriGolonganBySkor($skorTotal) : null,
            'rekomendasi' => $subGolongan ? 'direkomendasikan' : 'perlu_review',
        ];
    }

    // Method untuk menentukan kategori golongan berdasarkan skor dari relasi sub golongan
    private function kategoriGolonganBySkor($skor)
    {
        // Jika ada rekomendasi sub golongan yang sudah diset, gunakan itu
        if ($this->rekomendasiSubGolongan && $this->rekomendasiSubGolongan->golonganPelanggan) {
            $subGolongan = $this->rekomendasiSubGolongan;
            return $subGolongan->golonganPelanggan->nama_golongan . ' (Skor ' . $subGolongan->skor_minimum . '-' . $subGolongan->skor_maksimum . ')';
        }

        // Jika belum ada, cari sub golongan yang sesuai dengan skor
        $subGolongan = SubGolonganPelanggan::whereNotNull('skor_minimum')
            ->whereNotNull('skor_maksimum')
            ->where('skor_minimum', '<=', $skor)
            ->where('skor_maksimum', '>=', $skor)
            ->with('golonganPelanggan')
            ->first();

        if ($subGolongan && $subGolongan->golonganPelanggan) {
            return $subGolongan->golonganPelanggan->nama_golongan . ' (Skor ' . $subGolongan->skor_minimum . '-' . $subGolongan->skor_maksimum . ')';
        }

        // Fallback jika tidak ditemukan sub golongan yang sesuai
        return 'Tidak ada golongan yang sesuai (Skor ' . $skor . ')';
    }

    // Method untuk update hasil survei berdasarkan scoring
    public function updateHasilSurvei($golonganId = null)
    {
        $rekomendasi = $this->getRekomendasiSubGolongan($golonganId);
        
        $this->update([
            'skor_total' => $rekomendasi['skor_total'],
            'hasil_survei' => $rekomendasi['rekomendasi'],
            'kategori_golongan' => $rekomendasi['kategori_golongan'],
        ]);

        return $rekomendasi;
    }
}
