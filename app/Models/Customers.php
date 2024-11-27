<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @extends Model<Customers>
 */
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
        'qr_id',
        'product_purchased',
        'store_id',
        'event_id'
    ];

    protected $hidden = [
        'qr_id',
        'product_purchased',
    ];

    protected $casts = [
        'full_name' => 'encrypted',
        'age' => 'encrypted',
        'region' => 'encrypted',
        'province'=> 'encrypted',
        'brgy' => 'encrypted',
        'street'=> 'encrypted',
        'mobile_number' => 'encrypted',
        'email' => 'encrypted',
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
