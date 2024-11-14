<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';

    protected $primaryKey = 'city_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'city_id',
        'region_id',
        'city_name',
        'city_status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate UUID for city_id when creating a new record
        static::creating(function ($model) {
            if (empty($model->city_id)) {
                $model->city_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with Region model
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }
}
