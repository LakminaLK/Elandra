<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\MongoProduct;
use App\Models\MongoCategory;
use App\Models\MongoBrand;

class ProductionMongoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🍃 Importing MongoDB data...');
        
        $exportFile = database_path('exports/mongodb_data_20251014_204455.json');
        
        if (!File::exists($exportFile)) {
            $this->command->error('Export file not found: ' . $exportFile);
            return;
        }
        
        $data = json_decode(File::get($exportFile), true);
        
        // Import products
        if (isset($data['collections']['products'])) {
            MongoProduct::truncate();
            foreach ($data['collections']['products'] as $product) {
                unset($product['_id']); // Remove _id to let MongoDB generate new ones
                MongoProduct::create($product);
            }
            $this->command->info('✓ Products imported: ' . count($data['collections']['products']));
        }
        
        // Import categories
        if (isset($data['collections']['categories'])) {
            MongoCategory::truncate();
            foreach ($data['collections']['categories'] as $category) {
                unset($category['_id']);
                MongoCategory::create($category);
            }
            $this->command->info('✓ Categories imported: ' . count($data['collections']['categories']));
        }
        
        // Import brands
        if (isset($data['collections']['brands'])) {
            MongoBrand::truncate();
            foreach ($data['collections']['brands'] as $brand) {
                unset($brand['_id']);
                MongoBrand::create($brand);
            }
            $this->command->info('✓ Brands imported: ' . count($data['collections']['brands']));
        }
        
        $this->command->info('✅ MongoDB data import completed!');
    }
}