<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @extends Model<RegionalCluster>
 */
class RegionalCluster extends Model
{
    use HasFactory;

    protected $table = 'regional_cluster';

    protected $primaryKey = 'cluster_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cluster_name',
        'cluster_status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate UUID for cluster_id when creating a new record
        static::creating(function ($model) {
            if (empty($model->cluster_id)) {
                $model->cluster_id = (string) Str::uuid();
            }
        });
    }
}
