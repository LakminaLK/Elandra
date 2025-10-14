<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDatabases extends Command
{
    protected $signature = 'db:export {--format=json : Export format (json|sql)}';
    protected $description = 'Export MySQL and MongoDB data for Railway deployment';

    public function handle()
    {
        $format = $this->option('format');
        $timestamp = date('Ymd_His');
        $exportDir = database_path('exports');

        // Create exports directory
        if (!File::exists($exportDir)) {
            File::makeDirectory($exportDir, 0755, true);
        }

        $this->info('🚀 Starting database export...');

        // Export MySQL data
        $this->exportMySQL($exportDir, $timestamp);

        // Export MongoDB data
        $this->exportMongoDB($exportDir, $timestamp);

        // Create seeder files
        $this->createSeeders($exportDir, $timestamp);

        $this->info('✅ Database export completed!');
        $this->info("📁 Files saved to: {$exportDir}");
    }

    private function exportMySQL($exportDir, $timestamp)
    {
        $this->info('🗄️ Exporting MySQL data...');

        try {
            // Get all table data
            $tables = ['users', 'sessions', 'cache', 'jobs', 'job_batches', 'failed_jobs'];
            $data = [];

            foreach ($tables as $table) {
                try {
                    $tableData = DB::connection('mysql')->table($table)->get();
                    $data[$table] = $tableData;
                    $this->line("  ✓ Exported {$table}: {$tableData->count()} records");
                } catch (\Exception $e) {
                    $this->warn("  ⚠ Table {$table} not found or error: " . $e->getMessage());
                }
            }

            // Save to JSON
            $filename = "mysql_data_{$timestamp}.json";
            $filepath = "{$exportDir}/{$filename}";
            
            File::put($filepath, json_encode([
                'exported_at' => now()->toISOString(),
                'database' => config('database.connections.mysql.database'),
                'tables' => $data
            ], JSON_PRETTY_PRINT));

            $this->info("✅ MySQL data exported to: {$filename}");
        } catch (\Exception $e) {
            $this->error("❌ MySQL export failed: " . $e->getMessage());
        }
    }

    private function exportMongoDB($exportDir, $timestamp)
    {
        $this->info('🍃 Exporting MongoDB data...');

        try {
            // Get MongoDB collections using the correct method
            $products = \App\Models\MongoProduct::all();
            $categories = \App\Models\MongoCategory::all();
            $brands = \App\Models\MongoBrand::all();

            $data = [
                'exported_at' => now()->toISOString(),
                'database' => config('database.connections.mongodb.database'),
                'collections' => [
                    'products' => $products->toArray(),
                    'categories' => $categories->toArray(),
                    'brands' => $brands->toArray()
                ]
            ];

            // Save to JSON
            $filename = "mongodb_data_{$timestamp}.json";
            $filepath = "{$exportDir}/{$filename}";
            
            File::put($filepath, json_encode($data, JSON_PRETTY_PRINT));

            $this->info("✅ MongoDB data exported to: {$filename}");
            $this->line("  ✓ Products: {$products->count()} records");
            $this->line("  ✓ Categories: {$categories->count()} records");
            $this->line("  ✓ Brands: {$brands->count()} records");
        } catch (\Exception $e) {
            $this->error("❌ MongoDB export failed: " . $e->getMessage());
        }
    }

    private function createSeeders($exportDir, $timestamp)
    {
        $this->info('🌱 Creating import seeders...');

        // Create MongoDB seeder
        $mongoSeeder = <<<PHP
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
        \$this->command->info('🍃 Importing MongoDB data...');
        
        \$exportFile = database_path('exports/mongodb_data_{$timestamp}.json');
        
        if (!File::exists(\$exportFile)) {
            \$this->command->error('Export file not found: ' . \$exportFile);
            return;
        }
        
        \$data = json_decode(File::get(\$exportFile), true);
        
        // Import products
        if (isset(\$data['collections']['products'])) {
            MongoProduct::truncate();
            foreach (\$data['collections']['products'] as \$product) {
                unset(\$product['_id']); // Remove _id to let MongoDB generate new ones
                MongoProduct::create(\$product);
            }
            \$this->command->info('✓ Products imported: ' . count(\$data['collections']['products']));
        }
        
        // Import categories
        if (isset(\$data['collections']['categories'])) {
            MongoCategory::truncate();
            foreach (\$data['collections']['categories'] as \$category) {
                unset(\$category['_id']);
                MongoCategory::create(\$category);
            }
            \$this->command->info('✓ Categories imported: ' . count(\$data['collections']['categories']));
        }
        
        // Import brands
        if (isset(\$data['collections']['brands'])) {
            MongoBrand::truncate();
            foreach (\$data['collections']['brands'] as \$brand) {
                unset(\$brand['_id']);
                MongoBrand::create(\$brand);
            }
            \$this->command->info('✓ Brands imported: ' . count(\$data['collections']['brands']));
        }
        
        \$this->command->info('✅ MongoDB data import completed!');
    }
}
PHP;

        File::put("{$exportDir}/ProductionMongoSeeder.php", $mongoSeeder);

        // Create import instructions
        $instructions = <<<MD
# Railway Deployment Instructions

## Files Generated
- `mysql_data_{$timestamp}.json` - MySQL database export
- `mongodb_data_{$timestamp}.json` - MongoDB database export
- `ProductionMongoSeeder.php` - Laravel seeder for MongoDB data

## Railway Setup Steps

### 1. Create Railway Project
1. Go to [railway.app](https://railway.app)
2. Click "Start a New Project"
3. Connect your GitHub repository: `Elandra`
4. Railway will auto-detect your Laravel application

### 2. Add Database Services
1. Click "Add Service" → "Database" → "MySQL"
2. Click "Add Service" → "Database" → "MongoDB"
3. Wait for both databases to deploy

### 3. Configure Environment Variables
Copy these variables to your Railway environment:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
LOG_LEVEL=info
SESSION_ENCRYPT=true
FORCE_HTTPS=true
```

### 4. Deploy Application
1. Railway will automatically deploy using your Dockerfile
2. Check deployment logs for any issues
3. Your app will be available at the generated Railway URL

### 5. Import Database Data
SSH into your Railway application and run:
```bash
# Import MongoDB data using the seeder
php artisan db:seed --class=ProductionMongoSeeder

# Create admin user
php artisan tinker --execute="\\App\\Models\\User::firstOrCreate(['email' => 'admin@elandra.com'], ['name' => 'Admin', 'password' => Hash::make('admin123'), 'is_admin' => true]);"
```

### 6. Test Your Application
- Visit your Railway URL
- Test admin login: admin@elandra.com / admin123
- Verify product listings (should show your MongoDB products)
- Test customer registration and login

## Custom Domain Setup
1. In Railway dashboard, go to Settings → Domains
2. Add your custom domain
3. Update DNS settings with provided CNAME
4. Update APP_URL in environment variables

Generated on: $(date)
MD;

        File::put("{$exportDir}/RAILWAY_DEPLOYMENT.md", $instructions);

        $this->info('✅ Seeder files created');
    }
}