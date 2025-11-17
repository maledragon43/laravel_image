# Quick Start Guide - Laravel Project Setup

## ⚡ Fast Setup (3 Steps)

### Step 1: Install Composer (if not installed)
1. Download Composer from: https://getcomposer.org/download/
2. Or use XAMPP's PHP to install:
   ```powershell
   cd D:\XAMPP\htdocs\laravel_image
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   php -r "unlink('composer-setup.php');"
   ```
3. Use local composer: `php composer.phar install` (if downloaded locally)

### Step 2: Run Setup Script
```powershell
cd D:\XAMPP\htdocs\laravel_image
.\setup.bat
```

### Step 3: Start Server
```powershell
php artisan serve
```
Then open: **http://localhost:8000**

---

## 📋 Manual Setup (If setup.bat doesn't work)

### 1. Install Dependencies
```powershell
cd D:\XAMPP\htdocs\laravel_image
composer install
# OR if composer is not global:
php composer.phar install
```

### 2. Create .env File
Create a file named `.env` in the project root with this content:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_image
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 3. Generate Application Key
```powershell
php artisan key:generate
```

### 4. Create Storage Directories
```powershell
New-Item -ItemType Directory -Force -Path storage\app\public\images\originals
New-Item -ItemType Directory -Force -Path storage\app\public\images\working
New-Item -ItemType Directory -Force -Path storage\app\public\images\history
New-Item -ItemType Directory -Force -Path storage\app\public\images\final
```

### 5. Create Storage Link
```powershell
php artisan storage:link
```

### 6. Start Server
```powershell
php artisan serve
```

---

## 🌐 Access Methods

### Method 1: Laravel Development Server (Easiest)
```powershell
php artisan serve
```
Access: **http://localhost:8000**

### Method 2: XAMPP Apache
1. Make sure Apache is running in XAMPP Control Panel
2. Access: **http://localhost/laravel_image/public**

---

## ✅ Verification Checklist

- [ ] Composer dependencies installed (`vendor` folder exists)
- [ ] `.env` file created
- [ ] Application key generated (`APP_KEY` in `.env` has a value)
- [ ] Storage directories created
- [ ] Storage link created (`public/storage` exists)
- [ ] Server running without errors

---

## 🔧 Troubleshooting

### "Composer not found"
- Install Composer globally, or
- Use `php composer.phar` instead of `composer`
- Download composer.phar to project folder

### "APP_KEY is missing"
Run: `php artisan key:generate`

### "Storage link failed"
- Delete `public\storage` if it exists
- Run: `php artisan storage:link`
- Run PowerShell as Administrator if needed

### "500 Internal Server Error"
- Check `.env` file exists
- Verify `APP_KEY` is set
- Check `storage` folder permissions

### "Class not found"
Run: `composer dump-autoload` or `php composer.phar dump-autoload`

---

## 📝 Notes

- This is a Laravel 10 Image Editor application
- No database required for basic functionality
- Images stored in `storage/app/public/images/`
- Uses Intervention Image library for image manipulation

---

## 🚀 After Setup

1. Open browser: http://localhost:8000
2. Upload images
3. Edit images (rotate, crop)
4. Save edited images

