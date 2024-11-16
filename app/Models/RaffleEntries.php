<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaffleEntries extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'raffle_entries';
    protected $primaryKey = 'entries_id';
    protected $fillable = [
        'customer_id',
        'event_id',
        'serial_number',
        'qr_id',
        'retail_store_code',
        'claim_status',
        'winner_status',
        'winner_record',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->entries_id)) {
                $model->entries_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
