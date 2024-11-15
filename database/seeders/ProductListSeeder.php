<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductList;

class ProductListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['IDEMITSU 4T SL/MA 20W-50 1L', 'Fully Synthetic', 2],
            ['IDEMITSU 4T SL/MA 10W-40 SS 1L', 'Fully Synthetic', 2],
            ['IDEMITSU 4T 10W-40 SL/MB S-S 1L', 'Fully Synthetic', 2],
            ['IRG3 10W-40 MA 1L', 'Fully Synthetic', 2],
            ['IRG3 10W-40 MB 1L', 'Fully Synthetic', 2],
            ['IRG5 10W-30 MB 1L ', 'Fully Synthetic', 2],
            ['IRG7 10W-40 MA2 1L', 'Fully Synthetic', 2],
            ['UN MOTOSPORT 200 SF/40 800ml', 'Fully Synthetic', 2],
            ['UN MOTOSPORT 200 SF/40 800ml', 'Fully Synthetic', 2],
            ['UN MOTOSPORT 500 20W-40 SL MA 1L', 'Semi Synthetic', 2],
            ['UN MOTOSPORT 500 20W-40SL/MA 800ml', 'Semi Synthetic', 2],
            ['UN MOTOSPORT 700 10W40 SN/MA2 1L', 'Semi Synthetic', 2],
            ['UN MOTOSPORT 4T SCOOTER 800ml', 'Semi Synthetic', 2],
            ['UN SCOOTER 500 20W40 SL MB 1L', 'Semi Synthetic', 2],
            ['UN SCOOTER 700 10W40 SL MB (SS) 1L', 'Semi Synthetic', 2],
            ['UN SCOOTER 1000 10W40 SL MB (FS) 1L', 'Semi Synthetic', 2],
            ['UN SCOOTER 1000 10W40 SL MB (FS) 800ml', 'Semi Synthetic', 2],
            ['UN MOTOSPORT SCOOTER GEAR OIL BUNDLE', 'Semi Synthetic', 2]
        ];


        foreach($products as $product){
            $list = new ProductList();

            $list->product_name = $product[0];
            $list->product_type = $product[1];
            $list->entries = $product[2];
            $list->save();
        }
    }
}
