# Gmail SMTP Setup Guide for Elandra

## 📧 **Email Configuration**

Your email configuration has been set up for: **elandra.live@gmail.com**

## 🔒 **Important: Gmail App Password Setup**

Since Gmail uses 2-factor authentication, you need to generate an **App Password** instead of using your regular Gmail password.

### Step 1: Enable 2-Factor Authentication
1. Go to [Google Account Settings](https://myaccount.google.com/)
2. Click "Security" in the left sidebar
3. Under "Signing in to Google", click "2-Step Verification"
4. Follow the steps to enable 2FA if not already enabled

### Step 2: Generate App Password
1. Go back to "Security" settings
2. Under "Signing in to Google", click "App passwords"
3. Select "Mail" as the app
4. Select "Other (custom name)" as the device
5. Enter "Elandra Laravel App" as the name
6. Click "Generate"
7. **Copy the 16-character password** (it will look like: `abcd efgh ijkl mnop`)

### Step 3: Update Your .env File
Replace `your_app_password_here` in your `.env` file with the app password:

```env
MAIL_PASSWORD=abcdefghijklmnop
```

**Note:** Use the app password WITHOUT spaces.

## 📨 **Current Configuration**

Your `.env` file is configured with:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=elandra.live@gmail.com
MAIL_PASSWORD=your_app_password_here  # Replace this!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="elandra.live@gmail.com"
MAIL_FROM_NAME="Elandra"
```

## 🧪 **Testing Email Configuration**

After setting up the app password, you can test the email configuration:

### Method 1: Test Password Reset
1. Go to `/admin/login`
2. Click "Forgot password?"
3. Enter: `admin@elandra.com`
4. Check if email is sent successfully

### Method 2: Laravel Tinker Test
Run this command in terminal:

```bash
php artisan tinker
```

Then execute:

```php
Mail::raw('Test email from Elandra', function ($message) {
    $message->to('elandra.live@gmail.com')->subject('Test Email');
});
```

## 🛡️ **Security Best Practices**

1. **Never share your app password**
2. **Use environment variables** (already configured)
3. **Regularly rotate app passwords**
4. **Monitor email sending logs**

## 📋 **Email Features Enabled**

Once configured, these features will work:

✅ **Admin Password Reset** - Sends reset links  
✅ **System Notifications** - Admin alerts  
✅ **User Registration** - Welcome emails (when implemented)  
✅ **Order Notifications** - Purchase confirmations (when implemented)  

## 🚨 **Troubleshooting**

### Common Issues:

**Error: "Username/Password not accepted"**
- Make sure you're using the app password, not your Gmail password
- Verify 2FA is enabled on your Google account

**Error: "Connection timeout"**
- Check if your hosting provider blocks port 587
- Try port 465 with SSL encryption instead

**Emails not being sent:**
- Check Laravel logs: `storage/logs/laravel.log`
- Verify Gmail account isn't locked or suspended

### Alternative Port Configuration:
If port 587 doesn't work, try:

```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

## 📞 **Support**

If you encounter issues:
1. Check the Laravel logs
2. Verify Google account settings
3. Test with Laravel Tinker
4. Contact system administrator

---

**Last Updated:** {{ date('Y-m-d') }}  
**Configuration Status:** ⏳ Pending App Password Setup