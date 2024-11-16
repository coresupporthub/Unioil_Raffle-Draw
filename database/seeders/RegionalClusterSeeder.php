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
        $region = [
            'NCR',
            'NORTH LUZON',
            'SOUTH LUZON',
            'PANAY-NEGROS',
            'SOUTH MINDANAO'
        ];

        foreach($region as $reg){
            $rc = new RegionalCluster();
            $rc->cluster_name = $reg;
            $rc->cluster_status = "Enable";
            $rc->save();
        }
    }
}
