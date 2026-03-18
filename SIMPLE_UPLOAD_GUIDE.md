# Simple Upload Guide - Zip & Upload via File Manager

## Step 1: Prepare Files to Zip

### ✅ What to INCLUDE in your ZIP:
- `app/` folder
- `bootstrap/` folder
- `config/` folder
- `database/` folder
- `public/` folder (IMPORTANT!)
- `resources/` folder
- `routes/` folder
- `storage/` folder (but delete all `.log` files inside `storage/logs/`)
- `artisan` file
- `composer.json`
- `composer.lock`
- `package.json` (if exists)
- `webpack.mix.js` (if exists)
- `.htaccess` (if exists in root)

### ❌ What to EXCLUDE from ZIP:
- `vendor/` folder (will install on server)
- `.env` file (will create new one on server)
- `node_modules/` folder (if exists)
- `.git/` folder (if exists)
- `storage/logs/*.log` files (delete these before zipping)

## Step 2: Create the ZIP File

1. Navigate to: `C:\xampp\htdocs\ihvninve\ihvninventory.ng\`
2. Select all folders/files EXCEPT the excluded ones above
3. Right-click → Send to → Compressed (zipped) folder
4. Name it: `ihvninventory.zip`

## Step 3: Upload via File Manager

1. Login to your hosting control panel (cPanel, Plesk, etc.)
2. Open **File Manager**
3. Navigate to your web root (usually `public_html/` or `www/`)
4. Upload the `ihvninventory.zip` file
5. Extract the ZIP file in File Manager
6. You should now have `ihvninventory.ng/` folder (or rename it as needed)

## Step 4: Install Dependencies (via SSH or Terminal in cPanel)

**Option A: Via SSH (Recommended)**
```bash
cd public_html/ihvninventory.ng
composer install --no-dev --optimize-autoloader
```

**Option B: Via cPanel Terminal**
- Open Terminal in cPanel
- Run the same commands above

**Option C: If no SSH access**
- Upload the `vendor/` folder separately (if it's not too large)
- Or contact your host to install dependencies

## Step 5: Create .env File on Server

1. In File Manager, navigate to your application folder
2. Create a new file named `.env`
3. Copy the content below and **UPDATE the values marked with ⚠️**:

```env
APP_NAME="IHVN Inventory"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### ⚠️ What to CHANGE in .env:

1. **APP_URL**: Change to your actual domain
   ```
   APP_URL=https://yourdomain.com
   ```

2. **Database Settings**: Get from your hosting control panel
   ```
   DB_DATABASE=your_actual_database_name
   DB_USERNAME=your_actual_db_username
   DB_PASSWORD=your_actual_db_password
   ```

3. **APP_KEY**: Generate after creating .env file (see Step 6)

## Step 6: Generate Application Key (via SSH/Terminal)

```bash
cd public_html/ihvninventory.ng
php artisan key:generate
```

This will automatically update the `APP_KEY` in your `.env` file.

## Step 7: Set File Permissions

In File Manager:
1. Right-click on `storage/` folder → Change Permissions → Set to `755`
2. Right-click on `bootstrap/cache/` folder → Change Permissions → Set to `755`
3. Right-click on `public/uploads/` folder → Change Permissions → Set to `755`

## Step 8: Import Database

1. Export your local database:
   - Open phpMyAdmin locally: `http://localhost/phpmyadmin`
   - Select your database
   - Click "Export" tab → "Go" → Save SQL file

2. Import to server:
   - Open phpMyAdmin on your hosting
   - Select/create your production database
   - Click "Import" tab → Choose SQL file → "Go"

## Step 9: Configure Web Server

### For cPanel:
1. Go to **Subdomains** or **Addon Domains**
2. Point your domain to: `public_html/ihvninventory.ng/public`
3. Or create a subdomain pointing to the `public/` folder

### Important:
- Your domain must point to the `public/` folder, NOT the root folder
- Example: `public_html/ihvninventory.ng/public` ← This is your web root

## Step 10: Run Final Commands (via SSH/Terminal)

```bash
cd public_html/ihvninventory.ng

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 11: Test Your Application

1. Visit your domain: `https://yourdomain.com`
2. You should see the login page
3. Login with your credentials
4. Test the dashboard and features

## Quick Checklist

- [ ] ZIP file created (excluding vendor, .env, node_modules)
- [ ] ZIP uploaded and extracted on server
- [ ] Dependencies installed (`composer install`)
- [ ] `.env` file created with production values
- [ ] Application key generated (`php artisan key:generate`)
- [ ] File permissions set (755 for storage, bootstrap/cache)
- [ ] Database imported
- [ ] Domain points to `public/` folder
- [ ] Migrations run (`php artisan migrate`)
- [ ] Cache cleared and rebuilt
- [ ] Application tested

## What Changed from Local to Production?

### In .env file:
1. ✅ `APP_ENV=local` → `APP_ENV=production`
2. ✅ `APP_DEBUG=true` → `APP_DEBUG=false`
3. ✅ `APP_URL=http://localhost/...` → `APP_URL=https://yourdomain.com`
4. ✅ Database credentials (use production database)
5. ✅ Mail settings (if using email features)

### Everything else stays the same!

## Troubleshooting

**500 Error?**
- Check `.env` file exists
- Check file permissions on `storage/` folder
- Check error logs in `storage/logs/laravel.log`

**Database Error?**
- Verify database credentials in `.env`
- Ensure database exists on server

**404 Error?**
- Ensure domain points to `public/` folder
- Check `.htaccess` exists in `public/` folder

**Permission Denied?**
- Set `storage/` and `bootstrap/cache/` to 755 permissions

---

**That's it! Your application should now be live online! 🚀**

