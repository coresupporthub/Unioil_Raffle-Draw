<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @extends Model<RetailStore>
 */
class RetailStore extends Model
{
    use HasFactory;

    protected $table = 'retail_store';

    protected $primaryKey = 'store_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cluster_id',
        'address',
        'area',
        'distributor',
        'retail_station',
        'rto_code',
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
        return $this->belongsTo(RegionalCluster::class, 'cluster_id', 'cluster_id');
    }
}
