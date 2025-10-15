# URGENT: Railway Environment Variables Check

Make sure these are set in your Railway project Variables:

## Database Connection (Railway auto-provides these when you add MySQL service):
The following should be automatically available:
- MYSQLHOST
- MYSQLPORT  
- MYSQLDATABASE
- MYSQLUSER
- MYSQLPASSWORD

## Manual Variables You Need to Add:
```
APP_ENV=production
APP_KEY=base64:IPo9E5RoKbuRKfmRGJSF2Ur7XxOIcruY0p/aSVCdlrY=
APP_DEBUG=false
APP_URL=https://elandra-production.up.railway.app

DB_CONNECTION=mysql

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error
```

## Database Environment Mapping:
Your .env should use Railway variables like this:
- DB_HOST=${MYSQLHOST}
- DB_PORT=${MYSQLPORT}
- DB_DATABASE=${MYSQLDATABASE}
- DB_USERNAME=${MYSQLUSER}
- DB_PASSWORD=${MYSQLPASSWORD}

## If Tables Still Don't Appear:
1. Check Railway logs for migration errors
2. Verify MySQL service is connected to your main app
3. Ensure environment variables are properly set