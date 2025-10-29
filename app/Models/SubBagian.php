<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubBagian extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sub_bagian';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['bagian_id', 'nama_sub_bagian'];

    public function bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian_id');
    }
}
