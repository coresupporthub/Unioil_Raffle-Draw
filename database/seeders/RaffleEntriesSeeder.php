<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RaffleEntriesSeeder extends Seeder
{
    public function run()
    {
        $raffleEntries = [
            [
                'entries_id' => Str::uuid(),
                'customer_id' => '48fae339-e53c-419d-9eb9-033b6b65f8db', // Replace with actual UUID from the 'customers' table
                'serial_number' => 'SN1234567890',
                'qr_id' => '9780b712-ccc0-4d90-8a8e-d37c71061df7', // Replace with actual UUID from the 'qr_codes' table
                'retail_store_code' => '12345',
                'claim_status' => 'none',
                'winner_status' => 'false',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'entries_id' => Str::uuid(),
                'customer_id' => '50992a2d-70d0-436e-96ae-2d7b9376c16b',
                'serial_number' => 'SN1234567891',
                'qr_id' => '2c9890f5-d4e9-42f5-bf64-e669e812bf6d',
                'retail_store_code' => '54321',
                'claim_status' => 'none',
                'winner_status' => 'false',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'entries_id' => Str::uuid(),
                'customer_id' => 'db5db26f-6e32-4519-9a02-31ae099ce222',
                'serial_number' => 'SN1234567892',
                'qr_id' => '59cc8951-0595-4293-acc4-e1f49a9bab0e',
                'retail_store_code' => '54321',
                'claim_status' => 'none',
                'winner_status' => 'false',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('raffle_entries')->insert($raffleEntries);
    }
}

