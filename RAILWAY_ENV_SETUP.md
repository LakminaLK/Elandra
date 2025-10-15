# 🚂 Railway Environment Variables Setup Guide

## Required Services on Railway

### 1. **MySQL Database Service**
Add a MySQL service to your Railway project. This will automatically provide:
- `MYSQLHOST`
- `MYSQLPORT` 
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`

### 2. **MongoDB Database Service**
Add a MongoDB service to your Railway project. This will automatically provide:
- `MONGOHOST`
- `MONGOPORT`
- `MONGOUSER`
- `MONGOPASSWORD`

## Manual Environment Variables to Set

Copy these variables to your Railway project environment:

```bash
# Application Configuration
APP_NAME="Elandra E-commerce"
APP_ENV=production
APP_KEY=base64:IPo9E5RoKbuRKfmRGJSF2Ur7XxOIcruY0p/aSVCdlrY=
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
APP_TIMEZONE=UTC

# MongoDB Database Name (manual)
MONGO_DB_DATABASE=elandra_products

# Sessions
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Cache & Queue
CACHE_STORE=database
QUEUE_CONNECTION=database

# File Storage
FILESYSTEM_DISK=public

# Mail Configuration (using your existing Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=elandra.live@gmail.com
MAIL_PASSWORD=qawvpmdnlhfotwst
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="elandra.live@gmail.com"
MAIL_FROM_NAME="Elandra"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info
```

## 🎯 Database Architecture

### **MySQL Database** - Used for:
- 👥 Users and authentication (`users` table)
- 🛒 Shopping carts (`carts` table)
- 📦 Orders and order items (`orders`, `order_items` tables)
- 🔐 Sessions (`sessions` table)
- 📊 Cache (`cache` table)

### **MongoDB Database** - Used for:
- 📱 Products catalog (`products` collection)
- 🏷️ Categories (`categories` collection)
- 🏢 Brands (`brands` collection)

## 🚀 Deployment Steps

1. **Create Railway Project**
2. **Add MySQL Service** 
3. **Add MongoDB Service**
4. **Set Manual Environment Variables** (from list above)
5. **Deploy Your Code**
6. **Railway will automatically use the Dockerfile**

## ✅ Verification Commands

After deployment, you can verify both databases are working:

### Check MySQL Connection:
```bash
php artisan tinker
>>> DB::connection('mysql')->select('show tables')
>>> User::count()
```

### Check MongoDB Connection:
```bash
php artisan tinker
>>> DB::connection('mongodb')->getMongoDB()->listCollections()
>>> App\Models\MongoProduct::count()
```

## 🔧 Features Working

- ✅ **Dual Database Architecture** - MySQL + MongoDB
- ✅ **User Authentication** - Registration, Login, Admin Panel
- ✅ **Product Catalog** - MongoDB with flexible schema
- ✅ **Shopping Cart** - MySQL with MongoDB product references
- ✅ **Order Management** - Complete order processing
- ✅ **Admin Dashboard** - Product, Order, Customer management
- ✅ **API Endpoints** - RESTful API for products
- ✅ **File Uploads** - Product images storage
- ✅ **Email System** - Order confirmations, notifications

## 🌐 Live URLs After Deployment

- **Frontend**: `https://your-app-name.up.railway.app`
- **Admin Panel**: `https://your-app-name.up.railway.app/admin`
- **API Documentation**: `https://your-app-name.up.railway.app/api/docs`

## 🔐 Default Admin Access
- **Email**: admin@elandra.com
- **Password**: admin123

The deploy script automatically creates this admin user on first deployment.