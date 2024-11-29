<?php

namespace Database\Seeders;

use App\Models\QrCode;
use App\Models\RetailStore;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['Rheyan' , 'rheyanjohnblancogwapo@gmail.com', 'Admin'],
            ['JP', 'jpubas@gmail.com', 'Admin'],
            ['Tisha', 'tishtizon@gmail.com', 'Admin'],
            ['Hazel', 'santiago.hazel03@gmail.com', 'Admin'],
            ['Faith Cipriano', 'emfscipriano@unioil.com', 'Super Admin'],
        ];

        foreach($users as $user){
            User::factory()->create([
                'name' => $user[0],
                'email' => $user[1],
                'password' => '12345678',
                'user_type' => $user[2]
            ]);
        }

        $this->call(ProductListSeeder::class);
        // $this->call(QrCodeSeeder::class);
        $this->call(RegionalClusterSeeder::class);
        // $this->call(RetailStoreSeeder::class);
        // $this->call(CustomerSeeder::class);
        // $this->call(EventSeeder::class);
        // $this->call(RaffleEntriesSeeder::class);

    }
}
