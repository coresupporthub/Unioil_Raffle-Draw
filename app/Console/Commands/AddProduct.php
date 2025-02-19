<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductList;

class AddProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-product {--name=} {--type=} {--quantity=}';

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
        $name = $this->option('name');
        $type = $this->option('type');
        $quantity = $this->option('quantity');

        if(!$name || !$type || !$quantity){
            $this->error('Incomplete data please provide --name=<value|string> --type=<value|string> --quantity=<value|integer>');
            return 1;
        }

        if(!is_string($name)){
            $this->error('Name must be a type of string add single or double quote after equal if this is the case');
            return 1;
        }

        if(!is_string($type)){
            $this->error('Type must be a type of string add single or double quote after equal if this is the case');
            return 1;
        }

        if($quantity > 2 || $quantity == 0){
            $this->error('Quantity can only be 1 for single entry or 2 for double entry. Other values are invalid');
            return 1;
        }

        $list = new ProductList();

        $list->product_name = $name;
        $list->product_type = $type;
        $list->entries = $quantity;
        $list->save();

        $this->info('Product has been successfully saved in the database');
    }
}
