<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 3) as $index) {
            DB::table('event')->insert([
                'event_id' => Str::uuid(),
                'event_name' => $faker->sentence(3),
                'event_start' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                'event_end' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
                'event_status' => $faker->randomElement(['Active', 'Inactive']),
            ]);
        }
    }
}
