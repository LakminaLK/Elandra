#!/bin/bash

echo "🚀 Elandra Pre-Deployment Verification"
echo "======================================"
echo ""

# Function to check file existence
check_file() {
    if [ -f "$1" ]; then
        echo "✅ $1"
        return 0
    else
        echo "❌ $1 (missing)"
        return 1
    fi
}

# Function to check directory existence
check_dir() {
    if [ -d "$1" ]; then
        echo "✅ $1/"
        return 0
    else
        echo "❌ $1/ (missing)"
        return 1
    fi
}

echo "📁 Essential Files Check:"
check_file "Dockerfile"
check_file "railway.json"
check_file ".env.production"
check_file "deploy.sh"
check_file "composer.json"
check_file "package.json"
echo ""

echo "📊 Database Export Files:"
check_dir "database/exports"
check_file "database/exports/mongodb_data_*.json" 2>/dev/null || echo "❌ MongoDB export files (run php artisan db:export)"
check_file "database/exports/mysql_data_*.json" 2>/dev/null || echo "❌ MySQL export files (run php artisan db:export)"
check_file "database/seeders/ProductionMongoSeeder.php"
echo ""

echo "🔧 Configuration Files:"
check_file "config/database.php"
check_file "docker/nginx.conf"
check_file "docker/default.conf"
check_file "docker/supervisord.conf"
echo ""

echo "📚 Documentation:"
check_file "RAILWAY_DEPLOYMENT_GUIDE.md"
check_file "ASSIGNMENT_EXCELLENCE_REPORT.md"
check_file "README.md"
echo ""

echo "🎨 Frontend Assets:"
check_file "vite.config.js"
check_file "tailwind.config.js"
check_file "postcss.config.js"
echo ""

echo "🧪 Health Check System:"
check_file "app/Http/Controllers/HealthController.php"
check_file "resources/views/admin/monitoring.blade.php"
echo ""

# Check if environment variables are set
echo "🔐 Environment Configuration:"
if grep -q "APP_KEY=base64:" .env.production; then
    echo "✅ APP_KEY configured"
else
    echo "❌ APP_KEY not configured in .env.production"
fi

if grep -q "MYSQLHOST" .env.production; then
    echo "✅ MySQL variables configured"
else
    echo "❌ MySQL variables not configured"
fi

if grep -q "MONGOHOST" .env.production; then
    echo "✅ MongoDB variables configured"
else
    echo "❌ MongoDB variables not configured"
fi
echo ""

# Check Laravel application
echo "🔍 Laravel Application Check:"
if command -v php &> /dev/null; then
    if php artisan --version &> /dev/null; then
        echo "✅ Laravel CLI working"
        
        # Check database connections
        echo "🗄️ Database Connectivity:"
        if php artisan tinker --execute="try { DB::connection('mysql')->getPdo(); echo 'MySQL: Connected'; } catch(Exception \$e) { echo 'MySQL: Failed - ' . \$e->getMessage(); }" 2>/dev/null | grep -q "Connected"; then
            echo "✅ MySQL connection working"
        else
            echo "⚠️ MySQL connection needs configuration"
        fi
        
        if php artisan tinker --execute="try { \App\Models\MongoProduct::count(); echo 'MongoDB: Connected'; } catch(Exception \$e) { echo 'MongoDB: Failed - ' . \$e->getMessage(); }" 2>/dev/null | grep -q "Connected"; then
            echo "✅ MongoDB connection working"
        else
            echo "⚠️ MongoDB connection needs configuration"
        fi
    else
        echo "❌ Laravel CLI not working"
    fi
else
    echo "❌ PHP not found"
fi
echo ""

echo "📦 Dependencies Check:"
if [ -d "vendor" ]; then
    echo "✅ Composer dependencies installed"
else
    echo "❌ Run: composer install"
fi

if [ -d "node_modules" ]; then
    echo "✅ Node.js dependencies installed"
else
    echo "❌ Run: npm install"
fi

if [ -d "public/build" ]; then
    echo "✅ Frontend assets built"
else
    echo "⚠️ Run: npm run build (optional for development)"
fi
echo ""

echo "🚀 Deployment Readiness Summary:"
echo "================================"
echo ""
echo "Ready for Railway deployment if all items above are ✅"
echo ""
echo "📋 Next Steps:"
echo "1. Commit and push all changes to GitHub"
echo "2. Create Railway project and add MySQL + MongoDB services"
echo "3. Configure environment variables in Railway"
echo "4. Deploy and import database data"
echo "5. Test your live application"
echo ""
echo "📖 Full deployment guide: RAILWAY_DEPLOYMENT_GUIDE.md"