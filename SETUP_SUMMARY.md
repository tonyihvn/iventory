# Quick Setup Summary - IHVN Inventory Management System

## Application Overview

**IHVN Inventory Management System** is a Laravel 7-based web application for managing inventory, facilities, users, and distribution tracking for the Institute of Human Virology Nigeria.

### Key Features:
- Multi-role user management (Super, Admin, Manager, Facility, User, Observer, DCT roles)
- Inventory tracking with categories (Laptops, Phones, Biometrics, Desktops, etc.)
- Facility and state-based organization
- Data Center Tools (DCT) module
- Stock management and distribution tracking
- Request/requisition system
- Reporting and analytics with charts
- Audit logging
- File uploads (images, PDFs, documents)

### Technology Stack:
- **Backend**: Laravel 7 (PHP 7.2.5+)
- **Frontend**: Materialize CSS, jQuery, DataTables, Highcharts
- **Database**: MySQL (InnoDB)
- **Server**: Apache/Nginx

---

## Local Setup (Quick Steps)

### Prerequisites:
- XAMPP running (Apache + MySQL)
- PHP 7.2.5+ (Note: PHP 8.2 may have compatibility issues with Laravel 7)
- Composer installed

### Setup Steps:

1. **Start XAMPP Services**
   - Start Apache
   - Start MySQL

2. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `ihvn_inventory`

3. **Configure .env File**
   - Location: `C:\xampp\htdocs\ihvninve\ihvninventory.ng\.env`
   - Update database credentials:
     ```
     DB_DATABASE=ihvn_inventory
     DB_USERNAME=root
     DB_PASSWORD=
     APP_URL=http://localhost/ihvninve/ihvninventory.ng/public
     ```

4. **Generate Application Key**
   ```bash
   cd C:\xampp\htdocs\ihvninve\ihvninventory.ng
   php artisan key:generate
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Access Application**
   - URL: `http://localhost/ihvninve/ihvninventory.ng/public`
   - Login with your credentials

**Note**: If you encounter PHP 8.2 compatibility issues, consider using PHP 7.4 or downgrading XAMPP PHP version.

---

## FileZilla Deployment (Quick Steps)

### Pre-Upload:
1. ✅ Application works locally
2. ✅ Dependencies installed (`vendor/` exists)
3. ✅ `.env` prepared for production

### Upload Process:

1. **Connect via FileZilla**
   - Host: Your server FTP address
   - Username/Password: Your credentials

2. **Upload Files**
   - Upload ALL folders EXCEPT:
     - ❌ `vendor/` (install on server)
     - ❌ `.env` (create on server)
     - ❌ `node_modules/` (if exists)
     - ❌ `.git/` (if version controlled)

3. **On Server (via SSH):**
   ```bash
   cd /path/to/ihvninventory.ng
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   chmod -R 755 storage bootstrap/cache
   ```

4. **Create .env on Server**
   - Copy structure from local `.env`
   - Update with production credentials

5. **Configure Web Server**
   - Point domain to `public/` folder
   - Ensure `.htaccess` exists in `public/`

6. **Import Database**
   - Export from local phpMyAdmin
   - Import to server phpMyAdmin

7. **Test**
   - Visit your domain
   - Test login and features

---

## Important Files & Directories

### Must Upload:
- ✅ `app/` - Application code
- ✅ `config/` - Configuration files
- ✅ `database/` - Migrations and seeds
- ✅ `public/` - Web root (IMPORTANT!)
- ✅ `resources/` - Views and assets
- ✅ `routes/` - Route definitions
- ✅ `storage/` - Storage directory (exclude logs)
- ✅ `bootstrap/` - Bootstrap files
- ✅ `artisan` - Artisan command file
- ✅ `composer.json` & `composer.lock`

### Don't Upload:
- ❌ `vendor/` - Install on server
- ❌ `.env` - Create on server
- ❌ `node_modules/` - Not needed
- ❌ `.git/` - Version control

---

## Common Issues & Solutions

### PHP Version Compatibility
**Issue**: PHP 8.2 with Laravel 7 causes errors  
**Solution**: Use PHP 7.4 or downgrade XAMPP PHP version

### 500 Internal Server Error
- Check `.env` file exists
- Check file permissions on `storage/` and `bootstrap/cache/`
- Check server error logs

### Database Connection Error
- Verify database credentials in `.env`
- Ensure database exists
- Check MySQL service is running

### Route Not Found (404)
- Verify `.htaccess` in `public/` directory
- Check Apache `mod_rewrite` is enabled
- Ensure document root points to `public/` folder

---

## Documentation Files

1. **APPLICATION_DESCRIPTION.md** - Detailed application overview
2. **LOCAL_SETUP_GUIDE.md** - Complete local setup instructions
3. **FILEZILLA_DEPLOYMENT_GUIDE.md** - Complete deployment guide
4. **SETUP_SUMMARY.md** - This quick reference (you are here)

---

## Support & Next Steps

1. **Test Locally First**: Ensure everything works before deploying
2. **Backup Database**: Always backup before deployment
3. **Test on Server**: Verify all features work after deployment
4. **Monitor Logs**: Check `storage/logs/laravel.log` for errors
5. **Security**: Set `APP_DEBUG=false` and `APP_ENV=production` on server

---

## Quick Commands Reference

```bash
# Local Development
php artisan migrate              # Run migrations
php artisan db:seed              # Seed database
php artisan cache:clear          # Clear cache
php artisan config:clear         # Clear config cache

# Production Deployment
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Ready to deploy?** Follow the **FILEZILLA_DEPLOYMENT_GUIDE.md** for detailed step-by-step instructions.

