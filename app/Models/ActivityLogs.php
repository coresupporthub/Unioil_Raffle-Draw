<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLogs extends Model
{
    protected $table = 'activity_logs';

    protected $primaryKey = 'act_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'action',
        'result',
        'device',
        'page_route',
        'api_calls',
        'request_type',
        'session_id',
        'sent_data',
        'response_data'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->act_id)) {
                $model->act_id = (string) Str::uuid();
            }
        });
    }

}
