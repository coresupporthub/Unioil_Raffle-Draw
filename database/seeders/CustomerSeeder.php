<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('customers')->insert([
                'customer_id' => Str::uuid(),
                'full_name' => $faker->name,
                'age' => $faker->numberBetween(18, 80),
                'region' => $faker->state,
                'province' => $faker->state,
                'city' => $faker->city,
                'brgy' => 'Brgy ' . $faker->word,
                'street' => $faker->streetAddress,
                'mobile_number' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'product_purchased' => $faker->word,
            ]);
        }

    }
}
