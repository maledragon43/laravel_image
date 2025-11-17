# How to Run Laravel Project on Windows with XAMPP

## Prerequisites
- ✅ XAMPP installed and running
- ✅ PHP 8.1 or higher (included in XAMPP)
- ✅ Composer installed globally
- ✅ MySQL running in XAMPP Control Panel

## Step-by-Step Setup Instructions

### Step 1: Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Start **Apache** service
3. Start **MySQL** service

### Step 2: Install Composer Dependencies
Open PowerShell or Command Prompt in the project directory and run:
```powershell
cd D:\XAMPP\htdocs\laravel_image
composer install
```

### Step 3: Create Environment File
Create a `.env` file in the project root. You can copy from `.env.example` if it exists, or create manually:

```powershell
# If .env.example exists:
copy .env.example .env

# Or create .env manually with these settings:
```

**Minimum .env file content:**
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost/laravel_image/public

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_image
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 4: Generate Application Key
```powershell
php artisan key:generate
```

### Step 5: Create Database (Optional - if using database)
1. Open **phpMyAdmin** (http://localhost/phpmyadmin)
2. Create a new database named `laravel_image`
3. Or skip this step if the project doesn't use a database

### Step 6: Create Storage Directories
```powershell
# Create required directories
New-Item -ItemType Directory -Force -Path storage\app\public\images\originals
New-Item -ItemType Directory -Force -Path storage\app\public\images\working
New-Item -ItemType Directory -Force -Path storage\app\public\images\history
New-Item -ItemType Directory -Force -Path storage\app\public\images\final
```

### Step 7: Create Storage Symbolic Link
```powershell
php artisan storage:link
```

### Step 8: Run Database Migrations (if applicable)
```powershell
php artisan migrate
```

---

## Running the Application

### Option 1: Using Laravel Development Server (Recommended for Development)
```powershell
php artisan serve
```
Then open: **http://localhost:8000**

### Option 2: Using XAMPP Apache (For Production-like Setup)

1. **Configure Apache Virtual Host** (Optional but recommended):
   - Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
   - Add:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "D:/XAMPP/htdocs/laravel_image/public"
       ServerName laravel_image.local
       <Directory "D:/XAMPP/htdocs/laravel_image/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
   - Add to `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1 laravel_image.local
   ```
   - Restart Apache
   - Access: **http://laravel_image.local**

2. **Or Access Directly via XAMPP**:
   - Make sure Apache is running
   - Open: **http://localhost/laravel_image/public**

---

## Quick Setup Script (Windows)

You can also use the provided `setup.bat` file:
```powershell
.\setup.bat
```

Then start the server:
```powershell
php artisan serve
```

---

## Troubleshooting

### Issue: "Class not found" or "Composer autoload error"
**Solution:** Run `composer install` or `composer dump-autoload`

### Issue: "Storage link failed"
**Solution:** 
- Delete `public\storage` if it exists
- Run `php artisan storage:link` again
- On Windows, you may need to run PowerShell as Administrator

### Issue: "Permission denied" on storage
**Solution:** 
- Right-click `storage` folder → Properties → Security
- Give "Users" full control

### Issue: "500 Internal Server Error"
**Solution:**
- Check `.env` file exists and has `APP_KEY` generated
- Check `storage` and `bootstrap/cache` folders are writable
- Check Apache error logs in `C:\xampp\apache\logs\error.log`

### Issue: Images not displaying
**Solution:**
- Run `php artisan storage:link`
- Check `storage/app/public/images/` directories exist
- Verify file permissions

### Issue: "No application encryption key"
**Solution:** Run `php artisan key:generate`

---

## Default Access URLs

- **Laravel Serve:** http://localhost:8000
- **XAMPP Direct:** http://localhost/laravel_image/public
- **Virtual Host:** http://laravel_image.local (if configured)

---

## Next Steps

1. ✅ Verify the application loads correctly
2. ✅ Test image upload functionality
3. ✅ Check all routes are working
4. ✅ Verify storage directories are accessible

---

## Notes

- This project appears to be a Laravel Image Editor application
- It uses Intervention Image library for image manipulation
- No database is required for basic functionality (image upload/editing)
- All images are stored in `storage/app/public/images/`

