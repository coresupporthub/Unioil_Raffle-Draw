<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueingStatusModel extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'queueing_statuses';
    protected $primaryKey = 'queue_id';
    protected $fillable = [
        'queue_number',
        'total_items',
        'items',
        'status',
        'entry_type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->queue_id)) {
                $model->queue_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
