<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'qr_codes';
    protected $primaryKey = 'qr_id';
    protected $fillable = [
        'code',
        'entry_type',
        'status',
        'image',
        'export_status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_id)) {
                $model->qr_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
