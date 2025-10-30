<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kas extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kas';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode',
        'nama_kas',
        'status',
        'alamat',
        'tunggakan',
        'biaya_admin',
        'deposit_mode',
    ];

    protected $casts = [
        'status' => 'boolean',
        'deposit_mode' => 'boolean',
        'tunggakan' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
    ];

    // Auto generate UUID saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
