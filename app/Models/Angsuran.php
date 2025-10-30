<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran';

    protected $fillable = [
        'id_rab',
        'periode_tagihan',
        'angsuran_ke',
        'nominal_angsuran',
        'sisa_pokok',
        'status_bayar',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'denda',
        'total_bayar',
        'catatan',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_oleh',
        'diperbarui_pada',
    ];

    protected $casts = [
        'nominal_angsuran' => 'decimal:2',
        'sisa_pokok' => 'decimal:2',
        'denda' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Relasi ke RAB
     */
    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class, 'id_rab');
    }

    /**
     * Accessor untuk periode tagihan dalam format readable
     */
    public function getPeriodeTagihanTextAttribute(): string
    {
        if (!$this->periode_tagihan) {
            return '-';
        }
        
        $year = substr($this->periode_tagihan, 0, 4);
        $month = substr($this->periode_tagihan, 4, 2);
        
        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        return $monthNames[$month] . ' ' . $year;
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopePeriode($query, $periode)
    {
        return $query->where('periode_tagihan', $periode);
    }

    /**
     * Scope untuk filter berdasarkan status bayar
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_bayar', $status);
    }

    /**
     * Scope untuk yang belum dibayar
     */
    public function scopeBelumBayar($query)
    {
        return $query->where('status_bayar', 'belum_bayar');
    }

    /**
     * Scope untuk yang sudah dibayar
     */
    public function scopeSudahBayar($query)
    {
        return $query->where('status_bayar', 'sudah_bayar');
    }

    /**
     * Scope untuk yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status_bayar', 'terlambat')
                    ->orWhere(function($q) {
                        $q->where('status_bayar', 'belum_bayar')
                          ->where('tanggal_jatuh_tempo', '<', now());
                    });
    }

    /**
     * Method untuk mengecek apakah angsuran terlambat
     */
    public function isTerlambat(): bool
    {
        return $this->status_bayar === 'belum_bayar' && 
               $this->tanggal_jatuh_tempo < now();
    }

    /**
     * Method untuk menghitung denda keterlambatan
     */
    public function hitungDenda(float $persentaseDenda = 2): float
    {
        if (!$this->isTerlambat()) {
            return 0;
        }

        $hariTerlambat = now()->diffInDays($this->tanggal_jatuh_tempo);
        $dendaPerHari = ($this->nominal_angsuran * $persentaseDenda) / 100;
        
        return $dendaPerHari * $hariTerlambat;
    }

    /**
     * Method untuk generate periode tagihan dari tanggal
     */
    public static function generatePeriodeTagihan(Carbon $tanggal): int
    {
        return (int) $tanggal->format('Ym');
    }
}
