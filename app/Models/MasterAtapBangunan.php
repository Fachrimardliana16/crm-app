<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAtapBangunan extends Model
{
    use HasFactory;

    protected $table = 'master_atap_bangunan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
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
