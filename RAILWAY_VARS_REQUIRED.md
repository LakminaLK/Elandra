# Railway Environment Variables Setup

You need to add these services and variables in Railway:

## Required Services:
1. **MySQL Database** (Add from Railway dashboard)
2. **MongoDB Database** (Add from Railway dashboard)

## Required Environment Variables:
Copy these to your Railway project settings:

```
APP_NAME=Elandra
APP_ENV=production
APP_KEY=base64:IPo9E5RoKbuRKfmRGJSF2Ur7XxOIcruY0p/aSVCdlrY=
APP_DEBUG=false
APP_URL=https://elandra-production.up.railway.app

MONGO_DB_DATABASE=elandra_products

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

MAIL_MAILER=log
```

Railway will automatically provide:
- MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD
- MONGOHOST, MONGOPORT, MONGOUSER, MONGOPASSWORD