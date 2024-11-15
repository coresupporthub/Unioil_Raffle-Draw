<?php

namespace Database\Seeders;

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
        User::factory(10)->create();

        $users = [
            ['Rheyan' , 'rheyanjohnblancogwapo@gmail.com'],
            ['JP', 'jpubas@gmail.com'],
            ['Tisha', 'tishtizon@gmail.com'],
            ['Hazel', 'santiago.hazel03@gmail.com']
        ];

        foreach($users as $user){
            User::factory()->create([
                'name' => $user[0],
                'email' => $user[1],
                'password' => '12345678'
            ]);
        }

        $this->call(ProductListSeeder::class);

        $this->call(CustomerSeeder::class);

        $this->call(RaffleEntriesSeeder::class);
        $this->call(EventSeeder::class);


    }
}
