<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'event_id' => Str::uuid(),
                'event_name' => 'Grand Raffle',
                'event_start' => '2024-11-15',
                'event_end' => '2024-12-01',
                'event_status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('event')->insert($events);
    }
}
