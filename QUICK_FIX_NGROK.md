# Quick Fix: Ngrok URL Issue

## Problem
Your images are trying to load from `http://localhost:8000` instead of your ngrok URL, causing:
- ❌ Mixed Content warnings
- ❌ Connection Refused errors
- ❌ Images not displaying

## Solution (3 Steps)

### Step 1: Update .env File

Open `.env` file and find this line:
```env
APP_URL=http://localhost:8000
```

Change it to:
```env
APP_URL=https://nicolas-pseudohistoric-credulously.ngrok-free.dev
```

**Important:**
- Use `https://` (not `http://`)
- No trailing slash `/`
- Exact URL: `https://nicolas-pseudohistoric-credulously.ngrok-free.dev`

### Step 2: Clear Config Cache

Run these commands:
```powershell
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear
```

### Step 3: Restart Laravel Server

1. Stop your current server (Ctrl+C in the terminal)
2. Start it again:
   ```powershell
   D:\xampp\php\php.exe artisan serve --host=0.0.0.0
   ```

### Step 4: Clear Browser Cache

- Press **Ctrl+Shift+Delete** to clear cache
- OR press **Ctrl+F5** for hard refresh
- OR open in Incognito/Private window

## Verify It's Fixed

1. Visit: `https://nicolas-pseudohistoric-credulously.ngrok-free.dev`
2. Open browser console (F12)
3. Upload an image
4. Check console - should see URLs like:
   ```
   https://nicolas-pseudohistoric-credulously.ngrok-free.dev/storage/images/working/...
   ```
   NOT:
   ```
   http://localhost:8000/storage/images/working/...
   ```

## Quick Script

Or run the helper script:
```powershell
.\FIX_NGROK_URL.bat
```

## Still Not Working?

1. **Check .env file:**
   - Make sure APP_URL is exactly: `https://nicolas-pseudohistoric-credulously.ngrok-free.dev`
   - No extra spaces
   - No quotes around the URL

2. **Verify config:**
   ```powershell
   D:\xampp\php\php.exe artisan tinker
   # Then type: config('app.url')
   # Should show: https://nicolas-pseudohistoric-credulously.ngrok-free.dev
   ```

3. **Check ngrok:**
   - Make sure ngrok is running: `ngrok http 8000`
   - Verify the URL matches your .env

4. **Restart everything:**
   - Stop Laravel server
   - Stop ngrok
   - Start ngrok: `ngrok http 8000`
   - Start Laravel: `D:\xampp\php\php.exe artisan serve --host=0.0.0.0`
   - Update .env with new ngrok URL (if it changed)
   - Clear config cache again

## Why This Happens

Laravel caches configuration for performance. When you change `.env`, you must:
1. Clear config cache: `php artisan config:clear`
2. Restart the server

Otherwise, Laravel keeps using the old cached value.

