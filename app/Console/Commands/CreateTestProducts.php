<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoProduct;

class CreateTestProducts extends Command
{
    protected $signature = 'mongodb:create-test-products';
    protected $description = 'Create test MongoDB products for assignment demonstration';

    public function handle()
    {
        $this->info('Creating MongoDB test products...');

        try {
            // Test Product 1 - Laptop
            $product1 = new MongoProduct([
                'name' => 'MongoDB Test Laptop',
                'sku' => 'MONGO-LAPTOP-001',
                'description' => 'High-performance laptop stored in MongoDB database for assignment demonstration.',
                'price' => 1299.99,
                'sale_price' => 999.99,
                'cost' => 800.00,
                'category' => 'electronics',
                'brand' => 'TechPro',
                'stock_quantity' => 15,
                'low_stock_threshold' => 3,
                'is_active' => true,
                'is_featured' => true,
                'track_quantity' => true,
                'weight' => 2.1,
                'length' => 35.0,
                'width' => 25.0,
                'height' => 2.0
            ]);
            $product1->save();
            $this->line("✅ Created laptop with ID: {$product1->_id}");

            // Test Product 2 - Smartphone
            $product2 = new MongoProduct([
                'name' => 'MongoDB Test Smartphone',
                'sku' => 'MONGO-PHONE-001',
                'description' => 'Latest smartphone with advanced features, stored in MongoDB.',
                'price' => 799.99,
                'cost' => 400.00,
                'category' => 'electronics',
                'brand' => 'SmartTech',
                'stock_quantity' => 30,
                'low_stock_threshold' => 5,
                'is_active' => true,
                'is_featured' => false,
                'track_quantity' => true,
                'weight' => 0.2,
                'length' => 15.0,
                'width' => 7.0,
                'height' => 0.8
            ]);
            $product2->save();
            $this->line("✅ Created smartphone with ID: {$product2->_id}");

            // Test Product 3 - Headphones (Low Stock)
            $product3 = new MongoProduct([
                'name' => 'MongoDB Test Headphones',
                'sku' => 'MONGO-AUDIO-001',
                'description' => 'Premium wireless headphones with noise cancellation.',
                'price' => 299.99,
                'sale_price' => 249.99,
                'cost' => 120.00,
                'category' => 'audio',
                'brand' => 'AudioMax',
                'stock_quantity' => 8,
                'low_stock_threshold' => 10,
                'is_active' => true,
                'is_featured' => true,
                'track_quantity' => true,
                'weight' => 0.3,
                'length' => 20.0,
                'width' => 18.0,
                'height' => 8.0
            ]);
            $product3->save();
            $this->line("✅ Created headphones with ID: {$product3->_id}");

            // Test Product 4 - Gaming Mouse (Out of Stock)
            $product4 = new MongoProduct([
                'name' => 'Gaming Mouse Pro',
                'sku' => 'MONGO-MOUSE-001',
                'description' => 'Professional gaming mouse with RGB lighting.',
                'price' => 89.99,
                'cost' => 35.00,
                'category' => 'accessories',
                'brand' => 'GameGear',
                'stock_quantity' => 0,
                'low_stock_threshold' => 5,
                'is_active' => true,
                'is_featured' => false,
                'track_quantity' => true,
                'weight' => 0.1,
                'length' => 12.0,
                'width' => 6.0,
                'height' => 4.0
            ]);
            $product4->save();
            $this->line("✅ Created gaming mouse with ID: {$product4->_id}");

            // Test Product 5 - Inactive Product
            $product5 = new MongoProduct([
                'name' => 'Discontinued Tablet',
                'sku' => 'MONGO-TABLET-001',
                'description' => 'This tablet model has been discontinued.',
                'price' => 399.99,
                'cost' => 200.00,
                'category' => 'electronics',
                'brand' => 'TabletCorp',
                'stock_quantity' => 5,
                'low_stock_threshold' => 2,
                'is_active' => false,
                'is_featured' => false,
                'track_quantity' => true,
                'weight' => 0.5,
                'length' => 25.0,
                'width' => 18.0,
                'height' => 1.0
            ]);
            $product5->save();
            $this->line("✅ Created inactive tablet with ID: {$product5->_id}");

            $this->newLine();
            $this->info('🎉 Successfully created 5 MongoDB test products!');
            $this->line('📊 Categories: electronics, audio, accessories');
            $this->line('🔄 Different statuses: active, inactive, featured, low-stock, out-of-stock');
            $this->line('💰 Various pricing scenarios including sale prices');
            $this->line('📦 Different stock levels for testing filters');
            $this->newLine();
            
        } catch (\Exception $e) {
            $this->error('❌ Error creating test products: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}