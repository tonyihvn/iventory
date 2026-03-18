# IHVN Inventory Management System - Application Description

## Overview
The **IHVN Inventory Management System** is a comprehensive web-based inventory tracking and management application developed for the Institute of Human Virology Nigeria (IHVN). It's built using Laravel 7 (PHP framework) and provides a complete solution for managing inventory items, facilities, users, and distribution tracking.

## Application Type
- **Framework**: Laravel 7 (PHP)
- **Frontend**: Materialize CSS, jQuery, DataTables
- **Database**: MySQL (InnoDB)
- **Server Requirements**: PHP 7.2.5+, MySQL, Apache/Nginx

## Key Features

### 1. **Inventory Management**
- Track inventory items with details like:
  - Item name, serial number, IHVN number, tag number
  - Category (Laptops, Phones, Biometrics, Desktop Computers, etc.)
  - Facility assignment
  - User assignment
  - Status tracking
- State-wise inventory tracking
- Category-based inventory views
- Data quality checks and fixes

### 2. **User Role Management**
The system supports multiple user roles with different access levels:
- **Super**: Full system access
- **Admin**: Administrative access
- **Manager**: State-level management
- **Facility**: Facility-level access
- **User**: Basic user access
- **Observer**: Read-only access
- **DCTAdmin**: Data Center Tools Admin
- **DCTManager**: Data Center Tools Manager
- **DCTUser**: Data Center Tools User

### 3. **Facility Management**
- Multi-facility support
- State-based facility organization
- Facility switching capability
- Facility-specific inventory views

### 4. **Data Center Tools (DCT) Module**
- DCT inventory management
- Stock management for DCT items
- Distribution tracking
- Utilization tracking
- Delivery confirmation
- Bulk operations support

### 5. **Stock Management**
- Stock tracking and monitoring
- Supply management
- Stock reports
- Quantity tracking

### 6. **Request Management**
- Item requisition system
- Request approval workflow
- Request status tracking (Pending, Approved, Delivered)
- Multi-user request handling

### 7. **Reporting & Analytics**
- Dashboard with visual charts (Highcharts)
- State-wise inventory reports
- Category-based reports
- Distribution reports
- Audit trail
- Data export capabilities (Excel)

### 8. **Concurrency Management**
- Asset concurrency tracking
- Bulk updates
- Import/Export functionality

### 9. **Additional Features**
- Audit logging for all actions
- Email notifications
- File uploads (images, PDFs, documents)
- Search and filtering capabilities
- DataTables for advanced table operations
- Breadcrumb navigation

## Technical Architecture

### Directory Structure
```
ihvninventory.ng/
├── app/                    # Application core
│   ├── Http/Controllers/   # Controllers
│   ├── Models/            # Eloquent models
│   └── Middleware/        # Custom middleware
├── config/                 # Configuration files
├── database/              # Migrations, seeds, factories
├── public/                # Public assets (web root)
├── resources/             # Views, JS, CSS
├── routes/                # Route definitions
├── storage/               # Logs, cache, uploads
└── vendor/                # Composer dependencies
```

### Key Dependencies
- **Laravel Framework**: ^7.0
- **Laravel UI**: ^2.1 (Authentication scaffolding)
- **Maatwebsite Excel**: ^3.1 (Excel import/export)
- **Laravel Breadcrumbs**: ^8.1
- **Materialize CSS**: Frontend framework
- **jQuery**: JavaScript library
- **DataTables**: Advanced table functionality
- **Highcharts**: Charting library

### Database
- **Type**: MySQL (InnoDB engine)
- **Default Database**: `sohealt2_inventory` (configurable)
- **Migrations**: 26 migration files
- **Seeders**: 18 seed files

## File Upload Structure
- Uploads are stored in `/public/uploads/` and `/uploads/`
- Supports: Images (JPG, PNG), PDFs, Documents (DOC)

## Security Features
- Role-based access control (RBAC)
- Authentication middleware
- CSRF protection
- Password hashing
- Audit logging

## Deployment Considerations

### For FileZilla Upload:
1. **Exclude Files/Folders**:
   - `/vendor/` (can be regenerated with `composer install`)
   - `/node_modules/` (if exists)
   - `/.env` (create new on server)
   - `/storage/logs/*` (keep directory, exclude log files)
   - `/.git/` (if version controlled)

2. **Required Server Settings**:
   - PHP 7.2.5 or higher
   - MySQL/MariaDB
   - Apache with mod_rewrite or Nginx
   - Composer (for dependency management)
   - Proper file permissions (755 for directories, 644 for files)
   - Write permissions for `/storage/` and `/bootstrap/cache/`

3. **Post-Upload Steps**:
   - Create `.env` file with production settings
   - Run `composer install --no-dev`
   - Run `php artisan key:generate`
   - Run `php artisan migrate`
   - Set proper file permissions
   - Configure web server to point to `/public` directory

## Application URL Structure
- Base URL: `/` (dashboard)
- Login: `/login`
- Register: `/register` (restricted)
- Main modules:
  - `/inventory` - Inventory management
  - `/dctools` - Data Center Tools
  - `/facilities` - Facility management
  - `/concurrencies` - Concurrency tracking
  - `/reports` - Reports
  - `/requests` - Request management

## Current Status
The application appears to be a production-ready system with:
- Complete authentication system
- Role-based access control
- Full CRUD operations
- Reporting capabilities
- File upload functionality
- Email notifications
- Audit logging

