# 🔄 Restart Laravel Server to Fix Upload

## The Issue
The GD extension is enabled in php.ini, but the running Laravel server hasn't picked up the change yet. You need to restart the server.

## Quick Fix Steps

### Step 1: Stop the Current Server
In the terminal/command prompt where `php artisan serve` is running:
- Press `Ctrl + C` to stop the server

### Step 2: Start the Server Again
```powershell
cd D:\XAMPP\htdocs\laravel_image
D:\xampp\php\php.exe artisan serve
```

### Step 3: Test Upload
- Refresh your browser
- Try uploading an image again
- It should work now!

## Alternative: If Using XAMPP Apache

If you're accessing via `http://localhost/laravel_image/public`:

1. **Open XAMPP Control Panel**
2. **Stop Apache** (click Stop button)
3. **Start Apache** again (click Start button)
4. **Refresh your browser**
5. **Try uploading again**

## Verification

After restarting, you can verify GD is working:

```powershell
# Check if GD is loaded
D:\xampp\php\php.exe -r "if (extension_loaded('gd')) { echo 'GD is loaded!'; } else { echo 'GD NOT loaded'; }"

# Check GD info
D:\xampp\php\php.exe -r "print_r(gd_info());"
```

## Why This Happens

When you enable an extension in php.ini:
- ✅ PHP CLI immediately picks it up (for command line)
- ❌ Running web server processes need to be restarted to load the new configuration

That's why you need to restart `php artisan serve` or Apache.

