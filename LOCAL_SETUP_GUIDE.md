# Local Setup Guide - IHVN Inventory Management System

## Prerequisites
- XAMPP installed and running (Apache + MySQL)
- PHP 7.2.5 or higher
- Composer installed globally
- Git (optional, for version control)

## Step-by-Step Local Setup

### Step 1: Verify XAMPP is Running
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service
4. Verify both services are running (green status)

### Step 2: Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Database name: `ihvn_inventory` (or your preferred name)
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"

### Step 3: Import Database (if you have SQL file)
1. In phpMyAdmin, select your database
2. Click "Import" tab
3. Choose the SQL file (`dctools.sql` or your database dump)
4. Click "Go"

### Step 4: Configure Environment File
1. Navigate to: `C:\xampp\htdocs\ihvninve\ihvninventory.ng\`
2. Check if `.env` file exists
3. If not, copy `.env.example` to `.env` (or create new `.env` file)
4. Update the following in `.env`:

```env
APP_NAME="IHVN Inventory"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost/ihvninve/ihvninventory.ng/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ihvn_inventory
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (optional for local)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 5: Generate Application Key
Open PowerShell or Command Prompt in the project directory:
```bash
cd C:\xampp\htdocs\ihvninve\ihvninventory.ng
php artisan key:generate
```

### Step 6: Install Dependencies
```bash
composer install
```

### Step 7: Run Database Migrations
```bash
php artisan migrate
```

### Step 8: Seed Database (Optional - if seeders exist)
```bash
php artisan db:seed
```

### Step 9: Set File Permissions
On Windows, ensure these directories are writable:
- `storage/`
- `storage/app/`
- `storage/framework/`
- `storage/logs/`
- `bootstrap/cache/`

### Step 10: Configure Virtual Host (Recommended)

#### Option A: Using XAMPP Virtual Host
1. Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Add:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/ihvninve/ihvninventory.ng/public"
    ServerName ihvninventory.local
    <Directory "C:/xampp/htdocs/ihvninve/ihvninventory.ng/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
3. Open `C:\Windows\System32\drivers\etc\hosts` (as Administrator)
4. Add: `127.0.0.1 ihvninventory.local`
5. Restart Apache

#### Option B: Direct Access (Simpler)
Access via: `http://localhost/ihvninve/ihvninventory.ng/public`

### Step 11: Test the Application
1. Open browser: `http://localhost/ihvninve/ihvninventory.ng/public` or `http://ihvninventory.local`
2. You should see the login page
3. If you have a default user, login with those credentials
4. If not, you may need to create a user via database or registration

### Step 12: Create Default Admin User (if needed)
If no users exist, you can create one via Tinker:
```bash
php artisan tinker
```
Then in Tinker:
```php
$user = new App\User();
$user->name = 'Admin';
$user->email = 'admin@ihvn.com';
$user->password = Hash::make('password123');
$user->role = 'Super';
$user->state = 'Lagos';
$user->save();
```

## Troubleshooting

### Issue: "500 Internal Server Error"
- Check `.env` file exists and has correct database credentials
- Check `storage/` and `bootstrap/cache/` have write permissions
- Check Apache error logs: `C:\xampp\apache\logs\error.log`

### Issue: "Class not found" or "Composer autoload error"
- Run: `composer dump-autoload`

### Issue: "Database connection error"
- Verify MySQL is running in XAMPP
- Check database credentials in `.env`
- Verify database exists in phpMyAdmin

### Issue: "Route not found" or "404 errors"
- Ensure `.htaccess` file exists in `public/` directory
- Check Apache `mod_rewrite` is enabled
- Verify virtual host configuration

### Issue: "Permission denied" errors
- Right-click on `storage/` and `bootstrap/cache/` folders
- Properties → Security → Edit → Add "Everyone" with Full Control

## Development Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate
php artisan migrate:refresh  # Reset and re-run all migrations

# Generate optimized autoloader
composer dump-autoload -o
```

## Next Steps After Setup
1. Login to the application
2. Explore the dashboard
3. Create test facilities, categories, and inventory items
4. Test different user roles
5. Review the application features

## Notes
- The application uses Materialize CSS for the frontend
- DataTables is used for advanced table features
- Highcharts is used for dashboard visualizations
- File uploads are stored in `/public/uploads/` directory

