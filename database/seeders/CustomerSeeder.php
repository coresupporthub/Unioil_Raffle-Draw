<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'customer_id' => Str::uuid(),
                'full_name' => 'John Doe',
                'age' => 35,
                'region' => 'Western Visayas',
                'province' => 'Iloilo',
                'city' => 'Iloilo City',
                'brgy' => 'Jaro',
                'street' => 'Luna Street',
                'mobile_number' => '09171234567',
                'email' => 'johndoe@example.com',
                'product_purchased' => 'Smartphone',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => Str::uuid(),
                'full_name' => 'Jane Smith',
                'age' => 29,
                'region' => 'Central Luzon',
                'province' => 'Pampanga',
                'city' => 'Angeles City',
                'brgy' => 'Balibago',
                'street' => 'Friendship Highway',
                'mobile_number' => '09181234567',
                'email' => 'janesmith@example.com',
                'product_purchased' => 'Laptop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => Str::uuid(),
                'full_name' => 'Carlos Garcia',
                'age' => 42,
                'region' => 'National Capital Region',
                'province' => 'Metro Manila',
                'city' => 'Quezon City',
                'brgy' => 'Fairview',
                'street' => 'Regalado Avenue',
                'mobile_number' => '09192234567',
                'email' => 'carlosgarcia@example.com',
                'product_purchased' => 'Tablet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
