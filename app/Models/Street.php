<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Street extends Model
{
    use HasFactory;

    protected $table = 'street';

    protected $primaryKey = 'street_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'street_id',
        'city_id',
        'street_name',
        'street_status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate UUID for street_id when creating a new record
        static::creating(function ($model) {
            if (empty($model->street_id)) {
                $model->street_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with City model
     */
    // public function city()
    // {
    //     return $this->belongsTo(City::class, 'city_id', 'city_id');
    // }
}
