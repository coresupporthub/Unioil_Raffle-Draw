<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'product_lists';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'product_type',
        'entries'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->product_id)) {
                $model->product_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
