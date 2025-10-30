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
        // Pembayaran Cicilan fields
        'tipe_pembayaran',
        'jumlah_cicilan',
        'mode_cicilan',
        'custom_angsuran_data',
        'nominal_per_cicilan',
        'periode_mulai_cicilan',
        'catatan_pembayaran',
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
        // Pembayaran Cicilan fields
        'custom_angsuran_data' => 'array',
        'nominal_per_cicilan' => 'decimal:2',
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

    public function angsuran(): HasMany
    {
        return $this->hasMany(Angsuran::class, 'id_rab', 'id_rab');
    }

    /**
     * Scope untuk RAB dengan pembayaran cicilan
     */
    public function scopeCicilan($query)
    {
        return $query->where('tipe_pembayaran', 'cicilan');
    }

    /**
     * Scope untuk RAB dengan pembayaran lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('tipe_pembayaran', 'lunas');
    }

    /**
     * Method untuk generate angsuran otomatis atau custom
     */
    public function generateAngsuran(): void
    {
        if ($this->tipe_pembayaran !== 'cicilan' || !$this->jumlah_cicilan) {
            return;
        }

        // Hapus angsuran existing jika ada
        $this->angsuran()->delete();

        $periodeStart = $this->periode_mulai_cicilan ?: (int) now()->format('Ym');
        
        if ($this->mode_cicilan === 'custom' && $this->custom_angsuran_data) {
            $this->generateCustomAngsuran($periodeStart);
        } else {
            $this->generateAutoAngsuran($periodeStart);
        }
    }

    /**
     * Generate angsuran dengan nominal sama (auto mode)
     */
    private function generateAutoAngsuran(int $periodeStart): void
    {
        if (!$this->nominal_per_cicilan) {
            return;
        }

        $totalPokok = $this->total_biaya_sambungan_baru;

        for ($i = 1; $i <= $this->jumlah_cicilan; $i++) {
            // Calculate periode (YYYYMM)
            $tanggalPeriode = \Carbon\Carbon::createFromFormat('Ym', $periodeStart)->addMonths($i - 1);
            $periode = (int) $tanggalPeriode->format('Ym');
            
            // Calculate sisa pokok
            $sisaPokok = $totalPokok - (($i - 1) * $this->nominal_per_cicilan);
            
            // Tanggal jatuh tempo (tanggal 20 setiap bulan)
            $tanggalJatuhTempo = $tanggalPeriode->copy()->day(20);

            $this->angsuran()->create([
                'periode_tagihan' => $periode,
                'angsuran_ke' => $i,
                'nominal_angsuran' => $this->nominal_per_cicilan,
                'sisa_pokok' => $sisaPokok,
                'status_bayar' => 'belum_bayar',
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'denda' => 0,
                'dibuat_oleh' => auth()->user()->name ?? 'System',
                'dibuat_pada' => now(),
            ]);
        }
    }

    /**
     * Generate angsuran dengan nominal custom berbeda-beda
     */
    private function generateCustomAngsuran(int $periodeStart): void
    {
        $customData = $this->custom_angsuran_data;
        $totalPokok = $this->total_biaya_sambungan_baru;
        $totalDibayar = 0;

        foreach ($customData as $index => $data) {
            $i = $index + 1;
            
            // Calculate periode (YYYYMM)
            $tanggalPeriode = \Carbon\Carbon::createFromFormat('Ym', $periodeStart)->addMonths($i - 1);
            $periode = (int) $tanggalPeriode->format('Ym');
            
            // Use custom nominal
            $nominalAngsuran = (float) $data['nominal'];
            $totalDibayar += $nominalAngsuran;
            
            // Calculate sisa pokok
            $sisaPokok = $totalPokok - $totalDibayar;
            
            // Tanggal jatuh tempo (tanggal 20 setiap bulan)
            $tanggalJatuhTempo = $tanggalPeriode->copy()->day(20);

            $this->angsuran()->create([
                'periode_tagihan' => $periode,
                'angsuran_ke' => $i,
                'nominal_angsuran' => $nominalAngsuran,
                'sisa_pokok' => max(0, $sisaPokok), // Pastikan tidak minus
                'status_bayar' => 'belum_bayar',
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'denda' => 0,
                'catatan' => $data['catatan'] ?? null,
                'dibuat_oleh' => auth()->user()->name ?? 'System',
                'dibuat_pada' => now(),
            ]);
        }
    }

    /**
     * Method untuk validate total custom angsuran
     */
    public function validateCustomAngsuran(): array
    {
        if (!$this->custom_angsuran_data || $this->mode_cicilan !== 'custom') {
            return ['valid' => true];
        }

        $totalCustom = collect($this->custom_angsuran_data)->sum('nominal');
        $totalBiaya = (float) $this->total_biaya_sambungan_baru;

        $valid = abs($totalCustom - $totalBiaya) < 0.01; // Toleransi 1 sen

        return [
            'valid' => $valid,
            'total_custom' => $totalCustom,
            'total_biaya' => $totalBiaya,
            'selisih' => $totalCustom - $totalBiaya,
        ];
    }
}
