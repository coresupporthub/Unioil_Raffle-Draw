<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductList as Product;

class ProductList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $product = Product::all();

        foreach($product as $prod){
            $entries = $prod->entries == 1 ? "Single Entry" : "Dual Entry";
            $this->info("| $prod->product_name | $prod->product_type | $entries |");
            $this->info("--------------------------------------------------------------");
        }
    }
}
