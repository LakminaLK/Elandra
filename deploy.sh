#!/bin/bash
set -e

echo "🚀 Starting Laravel deployment process for Railway..."

# Check environment
if [ "$APP_ENV" = "production" ]; then
    echo "🌍 Production environment detected"
else
    echo "⚠️ Non-production environment: $APP_ENV"
fi

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Clear and cache configurations
echo "🗑️ Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --show
fi

# Wait for database connections
echo "⏳ Waiting for database connections..."

# Wait for MySQL
echo "🔌 Checking MySQL connection..."
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if php artisan tinker --execute="try { DB::connection('mysql')->getPdo(); echo 'MySQL Connected'; } catch(Exception \$e) { echo 'MySQL Failed: ' . \$e->getMessage(); exit(1); }" 2>/dev/null; then
        echo "✅ MySQL connection successful"
        break
    else
        echo "⏳ Attempt $attempt/$max_attempts: MySQL not ready, waiting..."
        sleep 5
        attempt=$((attempt + 1))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ MySQL connection failed after $max_attempts attempts"
    exit 1
fi

# Wait for MongoDB
echo "🔌 Checking MongoDB connection..."
attempt=1
while [ $attempt -le $max_attempts ]; do
    if php artisan tinker --execute="try { DB::connection('mongodb')->getCollection('test')->count(); echo 'MongoDB Connected'; } catch(Exception \$e) { echo 'MongoDB Failed: ' . \$e->getMessage(); exit(1); }" 2>/dev/null; then
        echo "✅ MongoDB connection successful"
        break
    else
        echo "⏳ Attempt $attempt/$max_attempts: MongoDB not ready, waiting..."
        sleep 5
        attempt=$((attempt + 1))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ MongoDB connection failed after $max_attempts attempts"
    exit 1
fi

# Run MySQL migrations
echo "🗄️ Running MySQL migrations..."
php artisan migrate --force --database=mysql

# Setup MongoDB collections and indexes
echo "🍃 Setting up MongoDB collections..."
php artisan tinker --execute="
try {
    // Create MongoDB collections with proper structure
    \$products = DB::connection('mongodb')->getCollection('products');
    \$categories = DB::connection('mongodb')->getCollection('categories');
    \$brands = DB::connection('mongodb')->getCollection('brands');
    
    // Create indexes for better performance
    \$products->createIndex(['name' => 'text', 'description' => 'text']);
    \$products->createIndex(['category' => 1]);
    \$products->createIndex(['brand' => 1]);
    \$products->createIndex(['status' => 1]);
    \$products->createIndex(['created_at' => -1]);
    
    \$categories->createIndex(['slug' => 1], ['unique' => true]);
    \$brands->createIndex(['slug' => 1], ['unique' => true]);
    
    echo 'MongoDB collections and indexes created successfully';
} catch (Exception \$e) {
    echo 'MongoDB setup failed: ' . \$e->getMessage();
    exit(1);
}
"

# Create storage symbolic link
echo "🔗 Linking storage..."
php artisan storage:link --force

# Cache configurations for production
echo "⚡ Caching configurations for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create necessary directories
echo "📁 Creating necessary directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set proper permissions
echo "� Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Optimize autoloader
echo "🔧 Optimizing autoloader..."
composer dump-autoload --optimize

# Create admin user if it doesn't exist
echo "👤 Checking for admin user..."
php artisan tinker --execute="
if (!\App\Models\User::where('email', 'admin@elandra.com')->exists()) {
    \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@elandra.com',
        'password' => Hash::make('admin123'),
        'email_verified_at' => now(),
        'is_admin' => true
    ]);
    echo 'Admin user created successfully';
} else {
    echo 'Admin user already exists';
}
"

echo "✅ Railway deployment complete!"
echo "🌐 Application should be available at: $APP_URL"
echo "👤 Admin login: admin@elandra.com / admin123"