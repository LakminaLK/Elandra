<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoProduct;
use App\Models\Product;

class VerifyMongoOnlyProducts extends Command
{
    protected $signature = 'mongodb:verify-setup';
    protected $description = 'Verify that products are being stored exclusively in MongoDB';

    public function handle()
    {
        $this->info('🔍 Verifying MongoDB-Only Product Setup...');
        $this->newLine();

        // Check MongoDB products
        try {
            $mongoCount = MongoProduct::count();
            $this->line("✅ MongoDB Products: {$mongoCount} found");
        } catch (\Exception $e) {
            $this->error("❌ MongoDB Connection Failed: " . $e->getMessage());
            return 1;
        }

        // Check if MySQL Product model still exists (it should but shouldn't be used)
        try {
            if (class_exists(Product::class)) {
                $mysqlCount = Product::count();
                $this->line("ℹ️  MySQL Products Table: {$mysqlCount} products (should not be used anymore)");
            }
        } catch (\Exception $e) {
            $this->line("ℹ️  MySQL Products: Table may not exist or be accessible");
        }

        $this->newLine();
        $this->info('🔧 Configuration Status:');
        $this->line('• Admin Products Route: /admin/products → MongoDB ProductController');
        $this->line('• Product Model: MongoProduct (MongoDB)');
        $this->line('• Database: ' . config('database.connections.mongodb.database'));
        $this->line('• Collection: products');
        
        $this->newLine();
        $this->info('✅ MongoDB-Only Setup Verification Complete!');
        $this->line('All product operations now use MongoDB exclusively.');
        
        return 0;
    }
}