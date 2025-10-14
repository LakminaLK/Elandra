#!/bin/bash
set -e

echo "📋 Elandra Database Export Tool"
echo "================================"

# Create exports directory
mkdir -p database/exports

# Export MySQL data
echo "🗄️ Exporting MySQL database..."

# Get database credentials from .env
source .env 2>/dev/null || echo "Warning: Could not source .env file"

# MySQL dump
if command -v mysqldump &> /dev/null; then
    echo "📤 Creating MySQL dump..."
    mysqldump -h${DB_HOST:-127.0.0.1} -P${DB_PORT:-3306} -u${DB_USERNAME:-root} -p${DB_PASSWORD} ${DB_DATABASE:-elandra_ssp2} \
        --single-transaction \
        --routines \
        --triggers \
        --add-drop-database \
        --create-options \
        --disable-keys \
        --extended-insert \
        --quick \
        --lock-tables=false > database/exports/mysql_backup_$(date +%Y%m%d_%H%M%S).sql
    echo "✅ MySQL export completed: database/exports/mysql_backup_$(date +%Y%m%d_%H%M%S).sql"
else
    echo "⚠️ mysqldump not found. Creating Laravel database export instead..."
    
    # Use Laravel to export data
    php artisan tinker --execute="
    \$users = DB::connection('mysql')->table('users')->get();
    \$orders = DB::connection('mysql')->table('orders')->get();
    \$carts = DB::connection('mysql')->table('carts')->get();
    \$addresses = DB::connection('mysql')->table('addresses')->get();
    
    \$export = [
        'users' => \$users,
        'orders' => \$orders,
        'carts' => \$carts,
        'addresses' => \$addresses,
        'exported_at' => now()
    ];
    
    file_put_contents('database/exports/mysql_data_' . date('Ymd_His') . '.json', json_encode(\$export, JSON_PRETTY_PRINT));
    echo 'MySQL data exported to JSON format';
    "
fi

# Export MongoDB data
echo "🍃 Exporting MongoDB database..."

if command -v mongoexport &> /dev/null; then
    echo "📤 Creating MongoDB export..."
    
    # Export products collection
    mongoexport --host ${MONGO_DB_HOST:-127.0.0.1}:${MONGO_DB_PORT:-27017} \
        --db ${MONGO_DB_DATABASE:-elandra_products} \
        --collection products \
        --out database/exports/mongodb_products_$(date +%Y%m%d_%H%M%S).json \
        --pretty
    
    # Export categories collection
    mongoexport --host ${MONGO_DB_HOST:-127.0.0.1}:${MONGO_DB_PORT:-27017} \
        --db ${MONGO_DB_DATABASE:-elandra_products} \
        --collection categories \
        --out database/exports/mongodb_categories_$(date +%Y%m%d_%H%M%S).json \
        --pretty
    
    # Export brands collection
    mongoexport --host ${MONGO_DB_HOST:-127.0.0.1}:${MONGO_DB_PORT:-27017} \
        --db ${MONGO_DB_DATABASE:-elandra_products} \
        --collection brands \
        --out database/exports/mongodb_brands_$(date +%Y%m%d_%H%M%S).json \
        --pretty
    
    echo "✅ MongoDB export completed"
else
    echo "⚠️ mongoexport not found. Creating Laravel MongoDB export instead..."
    
    # Use Laravel to export MongoDB data
    php artisan tinker --execute="
    try {
        \$products = DB::connection('mongodb')->collection('products')->get();
        \$categories = DB::connection('mongodb')->collection('categories')->get();
        \$brands = DB::connection('mongodb')->collection('brands')->get();
        
        \$mongoExport = [
            'products' => \$products,
            'categories' => \$categories,
            'brands' => \$brands,
            'exported_at' => now()
        ];
        
        file_put_contents('database/exports/mongodb_data_' . date('Ymd_His') . '.json', json_encode(\$mongoExport, JSON_PRETTY_PRINT));
        echo 'MongoDB data exported to JSON format';
    } catch (Exception \$e) {
        echo 'MongoDB export failed: ' . \$e->getMessage();
    }
    "
fi

# Create import instructions
cat > database/exports/README.md << EOF
# Database Export Files

## Files Generated
- MySQL dump: \`mysql_backup_*.sql\` or \`mysql_data_*.json\`
- MongoDB data: \`mongodb_*.json\` files or \`mongodb_data_*.json\`

## Import Instructions for Railway

### MySQL Import
1. In Railway dashboard, go to your MySQL service
2. Connect via Railway CLI or web terminal
3. Import the SQL file:
   \`\`\`bash
   mysql -h <MYSQLHOST> -P <MYSQLPORT> -u <MYSQLUSER> -p<MYSQLPASSWORD> <MYSQLDATABASE> < mysql_backup_*.sql
   \`\`\`

### MongoDB Import
1. In Railway dashboard, go to your MongoDB service
2. Connect via Railway CLI or mongosh
3. Import collections:
   \`\`\`bash
   mongoimport --host <MONGOHOST>:<MONGOPORT> --db elandra_products --collection products --file mongodb_products_*.json
   mongoimport --host <MONGOHOST>:<MONGOPORT> --db elandra_products --collection categories --file mongodb_categories_*.json
   mongoimport --host <MONGOHOST>:<MONGOPORT> --db elandra_products --collection brands --file mongodb_brands_*.json
   \`\`\`

## Alternative: Laravel Seeders
If direct import fails, you can use the provided JSON files with Laravel seeders on the production server.

Generated on: $(date)
EOF

echo ""
echo "✅ Database export completed!"
echo "📁 Export files saved to: database/exports/"
echo "📖 Import instructions: database/exports/README.md"
echo ""
echo "🚀 Next steps:"
echo "1. Commit and push these export files to your repository"
echo "2. Set up Railway project with MySQL and MongoDB services"
echo "3. Import the data using Railway's database tools"