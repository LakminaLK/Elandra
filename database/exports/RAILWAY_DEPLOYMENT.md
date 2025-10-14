# Railway Deployment Instructions

## Files Generated
- `mysql_data_20251014_204455.json` - MySQL database export
- `mongodb_data_20251014_204455.json` - MongoDB database export
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
php artisan tinker --execute="\App\Models\User::firstOrCreate(['email' => 'admin@elandra.com'], ['name' => 'Admin', 'password' => Hash::make('admin123'), 'is_admin' => true]);"
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