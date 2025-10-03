<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoProduct;

class MarkFeaturedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:mark-featured {count=6 : Number of products to mark as featured}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark products as featured for the homepage display';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        
        $this->info("Checking products...");
        $totalProducts = MongoProduct::count();
        $this->info("Total products: {$totalProducts}");
        
        $featuredCount = MongoProduct::where('is_featured', true)->count();
        $this->info("Currently featured products: {$featuredCount}");
        
        // Mark first X products as featured
        $products = MongoProduct::take($count)->get();
        $updated = 0;
        
        foreach ($products as $product) {
            $product->is_featured = true;
            $product->save();
            $updated++;
            $this->line("Marked '{$product->name}' as featured");
        }
        
        $this->info("Updated {$updated} products as featured");
        
        $newFeaturedCount = MongoProduct::where('is_featured', true)->count();
        $this->info("New featured products count: {$newFeaturedCount}");
        
        return Command::SUCCESS;
    }
}
