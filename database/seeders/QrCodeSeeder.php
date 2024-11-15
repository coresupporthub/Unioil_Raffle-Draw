<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class QrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {

            $code = strtoupper($faker->bothify('QR####??'));

            DB::table('qr_codes')->insert([
                'qr_id' => Str::uuid(),
                'code' => $code,
                'entry_type' => $faker->randomElement(['Single Entry QR Code', 'Dual Entry QR Code']),
                'status' => 'unused',
                'image' => "{$code}.png",
                'export_status' => 'none',
            ]);
        }
    }
}
