<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoProduct;

class UpdateStockQuantities extends Command
{
    protected $signature = 'test:update-stock';
    protected $description = 'Update some products to have low stock for testing';

    public function handle()
    {
        $this->info('Current Product Stock Quantities:');
        $this->line('================================');

        $products = MongoProduct::select(['name', 'stock_quantity'])->take(10)->get();

        foreach($products as $product) {
            $stock = $product->stock_quantity ?? 0;
            $this->line($product->name . " - Stock: " . $stock);
        }

        $this->line('');
        $this->info('Updating some products to have low stock for testing...');

        // Update some products to have low stock quantities for testing
        $products = MongoProduct::take(3)->get();

        foreach($products as $index => $product) {
            $stockValues = [1, 2, 5]; // Different low stock values
            $product->stock_quantity = $stockValues[$index];
            $product->save();
            
            $this->line("Updated: " . $product->name . " - New Stock: " . $product->stock_quantity);
        }

        $this->info('Done!');
        
        return 0;
    }
}