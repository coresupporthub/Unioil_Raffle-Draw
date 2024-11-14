<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory;

    protected $table = 'region';

    protected $primaryKey = 'region_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'region_id',
        'cluster_id',
        'region_name',
        'region_status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate UUID for region_id when creating a new record
        static::creating(function ($model) {
            if (empty($model->region_id)) {
                $model->region_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with RegionalCluster model
     */
    public function cluster()
    {
        return $this->belongsTo(RegionalCluster::class, 'cluster_id', 'cluster_id');
    }
}
