# Debug: Image Not Displaying After Upload

## Issue
Upload shows success but image doesn't display.

## Quick Checks

### 1. Check Browser Console
1. Open browser Developer Tools (F12)
2. Go to **Console** tab
3. Look for errors like:
   - `Failed to load resource: 404`
   - `CORS error`
   - Image URL errors

### 2. Check Network Tab
1. Open Developer Tools (F12)
2. Go to **Network** tab
3. Upload an image
4. Look for the image request
5. Check if it returns 404 or 200

### 3. Verify Image URL
After upload, check the response in browser console:
```javascript
// In browser console after upload
// The response should contain image URLs like:
// http://localhost:8000/storage/images/working/uuid.jpg
```

### 4. Test Storage URL Directly
Try accessing a storage URL directly in browser:
```
http://localhost:8000/storage/images/working/[filename]
```

### 5. Check if Images Are Actually Saved
Run this command to see if files exist:
```powershell
Get-ChildItem storage\app\public\images\working -Recurse
```

## Common Issues & Fixes

### Issue 1: Storage Link Not Working
**Fix:**
```powershell
# Remove old link
Remove-Item public\storage -Force -ErrorAction SilentlyContinue

# Create new link
D:\xampp\php\php.exe artisan storage:link
```

### Issue 2: Wrong URL Format
The URL should be: `http://localhost:8000/storage/images/working/filename.jpg`

If it's different, check:
- `.env` file has `APP_URL=http://localhost:8000`
- `config/filesystems.php` has correct URL configuration

### Issue 3: Images Not Saving
Check:
- Storage directories exist and are writable
- PHP has write permissions
- No errors in `storage/logs/laravel.log`

### Issue 4: CORS or Path Issues
If using `php artisan serve`, make sure:
- URL is `http://localhost:8000` (not `http://127.0.0.1:8000`)
- Storage link is properly created

## Debug Steps

1. **Check upload response:**
   - Open browser console (F12)
   - Upload an image
   - Check the response JSON
   - Verify the `url` field in the response

2. **Test image URL:**
   - Copy the image URL from the response
   - Paste it directly in browser address bar
   - See if image loads

3. **Check file system:**
   ```powershell
   # List uploaded files
   Get-ChildItem storage\app\public\images\working
   Get-ChildItem storage\app\public\images\originals
   ```

4. **Check Laravel logs:**
   ```powershell
   Get-Content storage\logs\laravel.log -Tail 30
   ```

## Quick Fix Commands

```powershell
# Recreate storage link
Remove-Item public\storage -Force -ErrorAction SilentlyContinue
D:\xampp\php\php.exe artisan storage:link

# Clear cache
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear

# Check permissions
icacls storage\app\public\images /grant Users:F /T
```

## Expected Behavior

After successful upload:
1. Response JSON contains: `{success: true, images: [{id: "...", url: "http://localhost:8000/storage/..."}]}`
2. Image file exists in `storage/app/public/images/working/`
3. Image URL is accessible in browser
4. Image displays in the "Uploaded Images" section

