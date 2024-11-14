<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RetailStore extends Model
{
    use HasFactory;

    protected $table = 'retail_store';

    protected $primaryKey = 'store_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'store_id',
        'city_id',
        'store_name',
        'store_code',
        'store_status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate UUID for store_id when creating a new record
        static::creating(function ($model) {
            if (empty($model->store_id)) {
                $model->store_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with Street model
     */
    public function street()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
}
