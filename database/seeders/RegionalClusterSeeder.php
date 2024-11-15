<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegionalCluster;

class RegionalClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 5; $i++){
            $rc = new RegionalCluster();
            $number = $i + 1;
            $rc->cluster_name = "Regional Cluster {$number}";
            $rc->cluster_status = "Enable";
            $rc->save();
        }
    }
}
