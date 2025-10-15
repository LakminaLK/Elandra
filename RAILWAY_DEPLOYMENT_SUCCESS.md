# 🚂 Railway Environment Setup Instructions

## 🎯 Your Deployment URL
Your app is live at: **https://elandra-production.up.railway.app**

## 📋 Step-by-Step Setup

### 1. Add Database Services
In your Railway dashboard:
1. Click "Add Service" → "Database" → "MySQL"
2. Click "Add Service" → "Database" → "MongoDB"

### 2. Set Environment Variables
Go to your Elandra service → Variables tab and add these:

**Core Application:**
```
APP_NAME=Elandra
APP_ENV=production
APP_KEY=base64:IPo9E5RoKbuRKfmRGJSF2Ur7XxOIcruY0p/aSVCdlrY=
APP_DEBUG=false
APP_URL=https://elandra-production.up.railway.app
APP_TIMEZONE=Asia/Colombo
```

**Database Settings:**
```
DB_CONNECTION=mysql
MONGO_DB_DATABASE=elandra_products
```

**Storage & Cache (Simple):**
```
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
```

**Mail (Gmail):**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=elandra.live@gmail.com
MAIL_PASSWORD=qawvpmdnlhfotwst
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=elandra.live@gmail.com
MAIL_FROM_NAME=Elandra
```

**Logging:**
```
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 3. Railway Auto-Provides
Railway will automatically provide these variables when you add database services:
- `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`
- `MONGOHOST`, `MONGOPORT`, `MONGOUSER`, `MONGOPASSWORD`

### 4. After Adding Variables
1. Your app will automatically redeploy
2. Visit: https://elandra-production.up.railway.app
3. Test the health endpoints:
   - `/health.txt` → should return "OK"
   - `/healthcheck.php` → should return "OK"

## 🎯 Key Differences from Local:
- **APP_ENV**: `production` (not `local`)
- **APP_DEBUG**: `false` (not `true`)
- **SESSION_DRIVER**: `file` (not `database` to avoid DB dependency)
- **CACHE_STORE**: `file` (not `database`)
- **QUEUE_CONNECTION**: `sync` (not `database`)
- **LOG_LEVEL**: `error` (not `debug`)

## ✅ Expected Features After Setup:
- 🏠 **Homepage**: Laravel welcome or your app
- 👥 **User Registration/Login**: Works with MySQL
- 📱 **Product Catalog**: Works with MongoDB
- 🛒 **Shopping Cart**: Session-based
- 📧 **Email**: Gmail SMTP working
- 👨‍💼 **Admin Panel**: `/admin` (if configured)

## 🔧 Admin Access
If you have admin seeding, default access should be:
- **Email**: admin@elandra.com
- **Password**: admin123

Your Elandra e-commerce platform is now live! 🚀