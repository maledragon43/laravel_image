# Quick Setup Guide

## Installation Steps

1. **Install Composer dependencies:**
   ```bash
   composer install
   ```

2. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

3. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

4. **Create storage directories:**
   ```bash
   mkdir -p storage/app/public/images/originals
   mkdir -p storage/app/public/images/working
   mkdir -p storage/app/public/images/history
   mkdir -p storage/app/public/images/final
   ```

5. **Create symbolic link for storage:**
   ```bash
   php artisan storage:link
   ```

6. **Set permissions (Linux/Mac):**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

7. **Start the server:**
   ```bash
   php artisan serve
   ```

8. **Access the application:**
   Open http://localhost:8000 in your browser

## Windows Users

For Windows, you can use PowerShell to create directories:

```powershell
New-Item -ItemType Directory -Force -Path storage\app\public\images\originals
New-Item -ItemType Directory -Force -Path storage\app\public\images\working
New-Item -ItemType Directory -Force -Path storage\app\public\images\history
New-Item -ItemType Directory -Force -Path storage\app\public\images\final
```

## Testing

1. Upload up to 5 images
2. Click "Edit Image" on any uploaded image
3. Test rotation with "Rotate 90°" button
4. Test cropping by clicking "Enable Crop" and dragging on the image
5. Test undo functionality
6. Save the final image

## Troubleshooting

- **Storage link error:** Make sure `public/storage` doesn't exist before running `php artisan storage:link`
- **Permission errors:** Ensure storage directories are writable
- **Image not displaying:** Check that storage link is created and images directory exists

