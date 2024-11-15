<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'full_name',
        'age',
        'region',
        'province',
        'city',
        'brgy',
        'street',
        'mobile_number',
        'email',
        'product_purchased'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->customer_id)) {
                $model->customer_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
