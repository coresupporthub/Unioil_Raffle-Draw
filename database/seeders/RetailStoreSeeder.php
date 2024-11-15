<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RetailStore;
use App\Models\RegionalCluster;
use Faker\Factory as Faker;
use App\Http\Services\Tools;
class RetailStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regional = RegionalCluster::all();
        $faker = Faker::create();
        for($i = 0; $i < 500; $i++){
            $regionalCluster = $regional->random();

            $store = new RetailStore();

            $store->cluster_id = $regionalCluster->cluster_id;
            $store->region_name = $faker->state;
            $store->city_name = $faker->city;
            $store->store_name = $faker->word;
            $store->store_code = Tools::genCode();
            $store->store_status = 'Enabled';
            $store->save();
        }
    }
}
