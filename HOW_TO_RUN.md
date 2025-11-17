# 🚀 How to Run Your Laravel Project

## Prerequisites Check

Before starting, ensure you have:
- ✅ XAMPP installed (usually in `C:\xampp`)
- ✅ XAMPP Control Panel running
- ✅ Apache and MySQL services started in XAMPP

## Step-by-Step Instructions

### Step 1: Add PHP to PATH (One-time setup)

**Option A: Add to System PATH**
1. Right-click "This PC" → Properties → Advanced System Settings
2. Click "Environment Variables"
3. Under "System Variables", find "Path" and click "Edit"
4. Add: `C:\xampp\php`
5. Click OK and restart PowerShell/Command Prompt

**Option B: Use Full Path (No PATH setup needed)**
Use `C:\xampp\php\php.exe` instead of `php` in all commands below.

---

### Step 2: Install Composer

**If Composer is NOT installed:**

1. **Download Composer:**
   - Visit: https://getcomposer.org/download/
   - Download `Composer-Setup.exe` and install it
   - OR download `composer.phar` to your project folder

2. **Verify installation:**
   ```powershell
   composer --version
   # OR if using local composer.phar:
   php composer.phar --version
   ```

---

### Step 3: Setup Project

**Open PowerShell or Command Prompt and run:**

```powershell
# Navigate to project directory
cd D:\XAMPP\htdocs\laravel_image

# Install dependencies (use one of these):
composer install
# OR if composer not in PATH:
C:\xampp\php\php.exe C:\path\to\composer.phar install
# OR if composer.phar is in project folder:
php composer.phar install
```

---

### Step 4: Create .env File

**Create a file named `.env` in the project root** (`D:\XAMPP\htdocs\laravel_image\.env`)

**Content:**
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

---

### Step 5: Generate Application Key

```powershell
cd D:\XAMPP\htdocs\laravel_image
php artisan key:generate
# OR if PHP not in PATH:
C:\xampp\php\php.exe artisan key:generate
```

---

### Step 6: Create Storage Directories

**In PowerShell:**
```powershell
cd D:\XAMPP\htdocs\laravel_image
New-Item -ItemType Directory -Force -Path storage\app\public\images\originals
New-Item -ItemType Directory -Force -Path storage\app\public\images\working
New-Item -ItemType Directory -Force -Path storage\app\public\images\history
New-Item -ItemType Directory -Force -Path storage\app\public\images\final
```

**OR in Command Prompt:**
```cmd
cd D:\XAMPP\htdocs\laravel_image
mkdir storage\app\public\images\originals
mkdir storage\app\public\images\working
mkdir storage\app\public\images\history
mkdir storage\app\public\images\final
```

---

### Step 7: Create Storage Link

```powershell
cd D:\XAMPP\htdocs\laravel_image
php artisan storage:link
# OR:
C:\xampp\php\php.exe artisan storage:link
```

---

### Step 8: Run the Application

**Option 1: Using Laravel Development Server (Recommended)**
```powershell
cd D:\XAMPP\htdocs\laravel_image
php artisan serve
# OR:
C:\xampp\php\php.exe artisan serve
```
Then open: **http://localhost:8000**

**Option 2: Using XAMPP Apache**
1. Make sure Apache is running in XAMPP Control Panel
2. Open browser: **http://localhost/laravel_image/public**

---

## 🎯 Quick Command Summary

If PHP and Composer are in PATH:
```powershell
cd D:\XAMPP\htdocs\laravel_image
composer install
php artisan key:generate
php artisan storage:link
php artisan serve
```

If PHP is NOT in PATH (use XAMPP's PHP):
```powershell
cd D:\XAMPP\htdocs\laravel_image
C:\xampp\php\php.exe C:\path\to\composer.phar install
C:\xampp\php\php.exe artisan key:generate
C:\xampp\php\php.exe artisan storage:link
C:\xampp\php\php.exe artisan serve
```

---

## ✅ Verification

After running `php artisan serve`, you should see:
```
INFO  Server running on [http://127.0.0.1:8000]
```

Open your browser and go to: **http://localhost:8000**

---

## 🔧 Common Issues & Solutions

### Issue: "php is not recognized"
**Solution:** Use full path: `C:\xampp\php\php.exe` or add PHP to PATH

### Issue: "composer is not recognized"
**Solution:** 
- Install Composer globally, OR
- Download `composer.phar` and use: `php composer.phar install`

### Issue: "APP_KEY is missing"
**Solution:** Run `php artisan key:generate`

### Issue: "Storage link failed"
**Solution:**
- Delete `public\storage` folder if it exists
- Run `php artisan storage:link` again
- Run PowerShell as Administrator

### Issue: "500 Internal Server Error"
**Solution:**
- Check `.env` file exists
- Verify `APP_KEY` has a value (run `php artisan key:generate`)
- Check `storage` folder is writable

### Issue: "Class not found"
**Solution:** Run `composer dump-autoload` or `composer install`

---

## 📁 Project Structure

```
laravel_image/
├── app/              # Application code
├── public/           # Web root (point Apache here)
├── storage/          # Storage for images, logs, etc.
├── .env             # Environment configuration (create this)
├── composer.json    # PHP dependencies
└── artisan          # Laravel command-line tool
```

---

## 🎉 Success!

Once you see the Laravel welcome page or your application, you're all set!

**Default URL:** http://localhost:8000 (with `php artisan serve`)
**OR:** http://localhost/laravel_image/public (with XAMPP Apache)

---

## 📞 Need Help?

1. Check that XAMPP Apache and MySQL are running
2. Verify PHP version: `php -v` (should be 8.1+)
3. Check `.env` file exists and has `APP_KEY` set
4. Ensure `storage` folder is writable
5. Check browser console for JavaScript errors
6. Check Laravel logs: `storage/logs/laravel.log`

