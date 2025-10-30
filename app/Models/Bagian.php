<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bagian extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bagian';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama_bagian'];

    public function subBagian(): HasMany
    {
        return $this->hasMany(SubBagian::class, 'bagian_id');
    }
}
