<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'event_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'event_name',
        'event_start',
        'event_end',
        'event_prize',
        'event_prize_image',
        'event_banner',
        'event_description',
        'event_status',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->event_id)) {
                $model->event_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
