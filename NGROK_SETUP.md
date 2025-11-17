# Ngrok Deployment Setup Guide

## Your Ngrok URL
**https://nicolas-pseudohistoric-credulously.ngrok-free.dev/**

## Required Changes

### 1. Update APP_URL in .env File

Open your `.env` file and update the `APP_URL`:

```env
APP_URL=https://nicolas-pseudohistoric-credulously.ngrok-free.dev
```

**Important:** 
- Use `https://` (not `http://`)
- Don't add a trailing slash `/`
- Make sure there are no spaces

### 2. Clear Configuration Cache

After updating `.env`, clear the config cache:

```powershell
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear
```

### 3. Verify Storage Link

Make sure the storage symbolic link exists:

```powershell
# Check if link exists
Test-Path public\storage

# If it doesn't exist, create it:
D:\xampp\php\php.exe artisan storage:link
```

### 4. Restart Laravel Server

After making changes, restart your Laravel development server:

1. Stop the current server (Ctrl+C in the terminal running `php artisan serve`)
2. Start it again:
   ```powershell
   D:\xampp\php\php.exe artisan serve --host=0.0.0.0
   ```

**Note:** Using `--host=0.0.0.0` allows ngrok to access your local server.

### 5. Update Ngrok Configuration

Make sure ngrok is pointing to your Laravel server:

```bash
ngrok http 8000
```

Or if you're using a different port:
```bash
ngrok http [your-port]
```

## What Was Already Updated

✅ **CORS Configuration** (`config/cors.php`)
- Added your ngrok domain to allowed origins
- Added ngrok pattern matching for any ngrok subdomain

## How Image URLs Work

Image URLs are generated using:
- `APP_URL` from `.env` file
- Plus `/storage/images/working/filename.jpg`

So with your ngrok URL, images will be accessible at:
```
https://nicolas-pseudohistoric-credulously.ngrok-free.dev/storage/images/working/[filename]
```

## Testing

1. **Test the main page:**
   - Visit: `https://nicolas-pseudohistoric-credulously.ngrok-free.dev/`
   - Should see the upload page

2. **Test image upload:**
   - Upload an image
   - Check browser console (F12) for any errors
   - Verify image displays correctly

3. **Test image URLs:**
   - After upload, right-click on the image
   - Select "Copy image address"
   - Should start with your ngrok URL

## Troubleshooting

### Images Not Displaying

1. **Check APP_URL:**
   ```powershell
   # In Laravel, you can check current config:
   D:\xampp\php\php.exe artisan tinker
   # Then type: config('app.url')
   ```

2. **Verify Storage Link:**
   ```powershell
   # The link should point to:
   # public/storage -> storage/app/public
   Get-Item public\storage | Select-Object Target
   ```

3. **Check Browser Console:**
   - Open Developer Tools (F12)
   - Look for 404 errors on image URLs
   - Check if URLs are using the correct domain

### CORS Errors

If you see CORS errors:
- The CORS config has been updated to include your ngrok domain
- Clear config cache: `php artisan config:clear`
- Restart the server

### Ngrok URL Changes

**Important:** If your ngrok URL changes (free ngrok URLs change on restart), you need to:
1. Update `APP_URL` in `.env` with the new URL
2. Update `config/cors.php` with the new URL (or use the pattern matching)
3. Clear config cache
4. Restart Laravel server

## Quick Setup Commands

Run these commands in order:

```powershell
# 1. Update .env (manually edit the file)
# Set: APP_URL=https://nicolas-pseudohistoric-credulously.ngrok-free.dev

# 2. Clear caches
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear

# 3. Verify storage link
D:\xampp\php\php.exe artisan storage:link

# 4. Restart server (in a new terminal)
D:\xampp\php\php.exe artisan serve --host=0.0.0.0
```

## Notes

- **Free ngrok URLs change** when you restart ngrok. Consider using a paid ngrok account for a static domain.
- **HTTPS:** Ngrok provides HTTPS automatically, so use `https://` in your APP_URL.
- **Storage:** Images are stored locally on your machine, but accessible via ngrok URL.
- **Session:** Sessions work with ngrok, but cookies may need proper domain configuration.

