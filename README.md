# Laravel Image Editor with Intervention Image

A Laravel application demonstrating image upload, rotation, and cropping using Intervention Image.

## Features

- Upload up to 5 images at once
- Rotate images in 90-degree steps
- Drag-and-drop cropping with visual crop box
- Undo functionality for all operations
- Save edited images to server

## Requirements

- PHP 8.1 or higher
- Composer
- Laravel 10+
- Intervention Image 2.7+

## Installation

1. **Clone or extract the project:**
   ```bash
   cd laravel_image
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Create storage link:**
   ```bash
   php artisan storage:link
   ```

6. **Create necessary directories:**
   ```bash
   mkdir -p storage/app/public/images/originals
   mkdir -p storage/app/public/images/working
   mkdir -p storage/app/public/images/history
   mkdir -p storage/app/public/images/final
   ```

7. **Set permissions (Linux/Mac):**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

8. **Start the development server:**
   ```bash
   php artisan serve
   ```

9. **Open in browser:**
   ```
   http://localhost:8000
   ```

## Usage

1. **Upload Images:**
   - Go to the home page
   - Select up to 5 images
   - Click "Upload Images"
   - Images will appear below with "Edit Image" buttons

2. **Edit Image:**
   - Click "Edit Image" on any uploaded image
   - Use "Rotate 90°" to rotate the image
   - Click "Enable Crop" to activate crop mode
   - Drag on the image to select crop area
   - Release mouse to apply crop
   - Use "Undo" to revert last operation
   - Click "Save Image" when done

## Project Structure

```
app/
  Http/
    Controllers/
      ImageController.php    # Main controller with Intervention Image operations
routes/
  web.php                     # Application routes
resources/
  views/
    layouts/
      app.blade.php          # Main layout
    images/
      index.blade.php        # Upload page
      edit.blade.php         # Image editor page
storage/
  app/
    public/
      images/
        originals/           # Original uploaded images
        working/             # Images being edited
        history/             # History for undo
        final/               # Final saved images
```

## Key Implementation Details

### Intervention Image Usage

The application uses Intervention Image in the following ways:

1. **Upload (`ImageController::upload`):**
   - Validates and processes uploaded images
   - Creates working copies for editing

2. **Rotate (`ImageController::rotate`):**
   - Uses `Image::make()` to load image
   - Uses `rotate(-90)` to rotate 90° clockwise
   - Saves state to history for undo

3. **Crop (`ImageController::crop`):**
   - Uses `crop(width, height, x, y)` method
   - Receives coordinates from frontend drag-and-drop
   - Maintains history for undo

4. **Undo (`ImageController::undo`):**
   - Restores previous state from history
   - Maintains operation stack

### Frontend Features

- Drag-and-drop crop selection with visual overlay
- Real-time image updates after operations
- Undo button state management
- Responsive image display

## Notes

- Images are stored in `storage/app/public/images/`
- Session is used to track image state and history
- History files are stored temporarily and can be cleaned up
- Maximum file size: 10MB per image
- Supported formats: JPEG, PNG, JPG, GIF, WebP

## Troubleshooting

**Images not displaying:**
- Run `php artisan storage:link` to create symbolic link
- Check `storage/app/public/images/` directory permissions

**Undo not working:**
- Check session configuration in `.env`
- Ensure `storage/app/public/images/history/` is writable

**Crop coordinates incorrect:**
- Ensure image has loaded completely before cropping
- Check browser console for JavaScript errors

## License

MIT

