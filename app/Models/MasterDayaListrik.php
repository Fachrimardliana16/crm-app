<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDayaListrik extends Model
{
    use HasFactory;

    protected $table = 'master_daya_listrik';

    protected $fillable = [
        'kode',
        'nama',
        'range_min',
        'range_max',
        'skor',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'skor' => 'integer',
        'urutan' => 'integer',
    ];
}
