# Troubleshooting Composer Installation Issues

## Current Issues

Your Composer installation is failing due to:
1. **Network connectivity issues** - Timeouts connecting to GitHub
2. **File lock errors** - Antivirus/Windows Defender blocking file operations

## Solutions

### Solution 1: Fix Network Issues

**Option A: Check Internet Connection**
- Ensure you have a stable internet connection
- Try accessing https://github.com in your browser
- If GitHub is blocked, you may need to use a VPN or proxy

**Option B: Configure Composer to Use Proxy (if behind corporate firewall)**
```powershell
D:\xampp\php\php.exe composer.phar config -g http-proxy http://proxy.example.com:8080
D:\xampp\php\php.exe composer.phar config -g https-proxy http://proxy.example.com:8080
```

**Option C: Increase Timeout**
```powershell
D:\xampp\php\php.exe composer.phar config -g process-timeout 600
```

### Solution 2: Fix File Lock Issues

**Option A: Temporarily Disable Antivirus (Recommended)**
1. Temporarily disable Windows Defender or your antivirus
2. Run the installation:
   ```powershell
   D:\xampp\php\php.exe composer.phar install
   ```
3. Re-enable antivirus after installation

**Option B: Add Project Folder to Antivirus Exclusions**
1. Open Windows Security (Windows Defender)
2. Go to Virus & threat protection → Manage settings
3. Under Exclusions, add folder: `D:\XAMPP\htdocs\laravel_image`
4. Try installation again

**Option C: Close Other Programs**
- Close any file managers, IDEs, or programs accessing the project folder
- Close any Composer processes in Task Manager
- Try installation again

### Solution 3: Manual Installation Steps

If automatic installation keeps failing, try these steps one at a time:

```powershell
# 1. Clean composer cache
D:\xampp\php\php.exe composer.phar clear-cache

# 2. Delete vendor folder if it exists (incomplete installation)
Remove-Item -Recurse -Force vendor -ErrorAction SilentlyContinue

# 3. Delete composer.lock and reinstall (if network is stable)
Remove-Item composer.lock -ErrorAction SilentlyContinue
D:\xampp\php\php.exe composer.phar install

# OR if you want to keep lock file, just retry:
D:\xampp\php\php.exe composer.phar install --no-scripts
```

### Solution 4: Use Alternative Method (If GitHub is Blocked)

If GitHub is completely blocked in your region:

1. **Use a VPN** to connect to GitHub
2. **Or download packages manually** (not recommended, very complex)
3. **Or use a mirror** (if available in your region)

## Quick Fix Commands

Try these in order:

```powershell
# 1. Wait a few minutes and retry (network might be temporarily down)
Start-Sleep -Seconds 60
D:\xampp\php\php.exe composer.phar install

# 2. If that fails, try with increased timeout
D:\xampp\php\php.exe composer.phar install --prefer-dist --no-interaction --timeout=600

# 3. If still failing, try installing without dev dependencies
D:\xampp\php\php.exe composer.phar install --no-dev

# 4. Last resort: install with verbose output to see exact error
D:\xampp\php\php.exe composer.phar install -vvv
```

## After Successful Installation

Once `composer install` completes successfully, you should see:
- `vendor/` directory created
- `vendor/autoload.php` file exists

Then continue with:
```powershell
# Generate .env file (if not exists)
# Create .env manually with content from HOW_TO_RUN.md

# Generate application key
D:\xampp\php\php.exe artisan key:generate

# Create storage directories
New-Item -ItemType Directory -Force -Path storage\app\public\images\originals
New-Item -ItemType Directory -Force -Path storage\app\public\images\working
New-Item -ItemType Directory -Force -Path storage\app\public\images\history
New-Item -ItemType Directory -Force -Path storage\app\public\images\final

# Create storage link
D:\xampp\php\php.exe artisan storage:link

# Start server
D:\xampp\php\php.exe artisan serve
```

## Verification

After installation, verify:
```powershell
# Check if vendor/autoload.php exists
Test-Path vendor\autoload.php

# Should return: True
```

## Still Having Issues?

If none of these solutions work:

1. **Check your firewall settings** - Allow PHP and Composer through firewall
2. **Check if you're behind a corporate proxy** - Configure proxy settings
3. **Try from a different network** - Use mobile hotspot to test
4. **Check Composer version** - Update if needed: `D:\xampp\php\php.exe composer.phar self-update`
5. **Check PHP version** - Should be 8.1+: `D:\xampp\php\php.exe -v`

## Alternative: Use Pre-installed Vendor Folder

If you have access to another machine with the same project:
1. Copy the `vendor` folder from that machine
2. Paste it into this project
3. Run: `D:\xampp\php\php.exe composer.phar dump-autoload`

This should work if the PHP versions are compatible.

