# FileZilla Deployment Guide - IHVN Inventory Management System

## Pre-Deployment Checklist

### 1. Prepare Local Application
- [ ] Application is working locally
- [ ] All dependencies are installed (`vendor/` folder exists)
- [ ] Database is backed up (if needed)
- [ ] `.env` file is ready for production (with production credentials)
- [ ] All file permissions are correct

### 2. Server Requirements
Ensure your hosting server has:
- [ ] PHP 7.2.5 or higher (7.4 recommended for Laravel 7)
- [ ] MySQL/MariaDB database
- [ ] Apache with mod_rewrite enabled OR Nginx
- [ ] Composer installed (for post-upload commands)
- [ ] SSH access (for running commands)

## Files to Upload via FileZilla

### ✅ Files/Folders TO Upload:
```
ihvninventory.ng/
├── app/                    ✅ Upload
├── bootstrap/              ✅ Upload
├── config/                 ✅ Upload
├── database/               ✅ Upload (migrations, seeds)
├── public/                 ✅ Upload (IMPORTANT: This is web root)
├── resources/              ✅ Upload
├── routes/                 ✅ Upload
├── storage/                ✅ Upload (but exclude logs/*)
├── .htaccess               ✅ Upload (if exists in root)
├── artisan                 ✅ Upload
├── composer.json           ✅ Upload
├── composer.lock           ✅ Upload
├── package.json            ✅ Upload (optional)
├── webpack.mix.js          ✅ Upload (optional)
├── phpunit.xml             ✅ Upload (optional)
└── README.md               ✅ Upload (optional)
```

### ❌ Files/Folders NOT to Upload:
```
├── vendor/                 ❌ DON'T Upload (install on server)
├── node_modules/           ❌ DON'T Upload (if exists)
├── .env                    ❌ DON'T Upload (create on server)
├── .git/                   ❌ DON'T Upload (if version controlled)
├── storage/logs/*.log      ❌ DON'T Upload log files
├── .env.example            ⚠️ Optional (for reference)
└── tests/                  ⚠️ Optional (not needed for production)
```

## Step-by-Step FileZilla Upload Process

### Step 1: Connect to Server
1. Open FileZilla
2. Enter your server details:
   - **Host**: Your server IP or domain (e.g., `ftp.yourdomain.com`)
   - **Username**: Your FTP username
   - **Password**: Your FTP password
   - **Port**: 21 (default) or 22 (SFTP)
3. Click "Quickconnect"

### Step 2: Navigate to Server Directory
1. Navigate to your web root directory on the server:
   - Usually: `/public_html/` or `/www/` or `/htdocs/`
   - Or a subdirectory: `/public_html/ihvninventory/`
2. Create a folder if needed: `ihvninventory.ng` or your preferred name

### Step 3: Upload Files
1. In FileZilla, navigate to: `C:\xampp\htdocs\ihvninve\ihvninventory.ng\`
2. Select all files and folders EXCEPT:
   - `vendor/` folder
   - `.env` file
   - `node_modules/` (if exists)
   - `.git/` (if exists)
3. Drag and drop selected files to server
4. Wait for upload to complete

### Step 4: Upload Vendor Dependencies (Alternative Method)
**Option A: Upload vendor folder (if small)**
- If your connection is fast, you can upload the entire `vendor/` folder
- This is faster but uses more bandwidth

**Option B: Install via SSH (Recommended)**
- Connect via SSH to your server
- Navigate to the application directory
- Run: `composer install --no-dev --optimize-autoloader`

### Step 5: Set Up Environment File on Server
1. On the server, create a new `.env` file in the application root
2. Copy the structure from your local `.env` but update with production values:

```env
APP_NAME="IHVN Inventory"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 6: Configure Web Server

#### For Apache:
1. Ensure `.htaccess` file exists in `public/` directory
2. Point your domain to the `public/` folder:
   - Via cPanel: Set document root to `/public_html/ihvninventory.ng/public`
   - Via Virtual Host:
   ```apache
   <VirtualHost *:80>
       ServerName yourdomain.com
       DocumentRoot /path/to/ihvninventory.ng/public
       <Directory /path/to/ihvninventory.ng/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

#### For Nginx:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/ihvninventory.ng/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Step 7: Run Post-Upload Commands (via SSH)

Connect to your server via SSH and run:

```bash
# Navigate to application directory
cd /path/to/ihvninventory.ng

# Install dependencies (if vendor wasn't uploaded)
composer install --no-dev --optimize-autoloader

# Generate application key (if not set in .env)
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/uploads
```

### Step 8: Set File Permissions

Set proper permissions on the server (via SSH or FileZilla):

```bash
# Directories should be 755
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public/uploads

# Files should be 644
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;
```

**Via FileZilla:**
1. Right-click on `storage/` folder → File Attributes
2. Set to: `755` (recursive)
3. Repeat for `bootstrap/cache/` and `public/uploads/`

### Step 9: Import Database

1. Export your local database:
   - Open phpMyAdmin locally
   - Select your database
   - Click "Export" → "Go"
   - Save the SQL file

2. Import to production:
   - Open phpMyAdmin on server
   - Select/create production database
   - Click "Import" → Choose SQL file → "Go"

### Step 10: Test the Application

1. Open browser: `https://yourdomain.com`
2. You should see the login page
3. Test login with production credentials
4. Check all major features:
   - Dashboard loads
   - Inventory pages work
   - File uploads work
   - Reports generate

## Troubleshooting

### Issue: "500 Internal Server Error"
- Check `.env` file exists and has correct values
- Check file permissions on `storage/` and `bootstrap/cache/`
- Check server error logs
- Verify `APP_DEBUG=true` temporarily to see errors

### Issue: "Class not found" errors
- Run: `composer dump-autoload -o`
- Verify `vendor/` folder is uploaded or installed

### Issue: "Database connection error"
- Verify database credentials in `.env`
- Check database exists on server
- Verify database user has proper permissions

### Issue: "Route not found" or "404 errors"
- Verify `.htaccess` file exists in `public/` directory
- Check Apache `mod_rewrite` is enabled
- Verify document root points to `public/` folder

### Issue: "Permission denied" errors
- Set proper file permissions (755 for directories, 644 for files)
- Ensure web server user owns the files

### Issue: "Storage directory not writable"
- Set permissions: `chmod -R 775 storage`
- Ensure web server user has write access

## Security Checklist

Before going live:
- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] Strong database passwords
- [ ] `.env` file is not publicly accessible
- [ ] File permissions are correct
- [ ] SSL certificate installed (HTTPS)
- [ ] Regular backups configured

## Post-Deployment

1. **Monitor Error Logs**: Check `storage/logs/laravel.log`
2. **Set Up Backups**: Regular database and file backups
3. **Performance**: Enable caching (already done in Step 7)
4. **Updates**: Keep Laravel and dependencies updated
5. **Security**: Regular security audits

## Notes

- The `public/` folder is your web root - all requests should go through it
- Never expose `.env` file publicly
- Keep `vendor/` folder secure (not accessible via web)
- Regular backups are essential
- Test thoroughly before making it live

