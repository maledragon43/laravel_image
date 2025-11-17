# Free Deployment Guide for Laravel Image Editor

## Best Free Hosting Options for Laravel

### 🥇 Option 1: Render (Recommended)
**Best for:** Easy deployment, automatic SSL, free tier available

**Free Tier Includes:**
- 750 hours/month (enough for 24/7 operation)
- 512 MB RAM
- Automatic SSL certificates
- Custom domains
- PostgreSQL database (free tier)

**Limitations:**
- Spins down after 15 minutes of inactivity (freezes on first request)
- Limited resources

**Deployment Steps:**
1. Sign up at [render.com](https://render.com)
2. Connect your GitHub repository
3. Create a new "Web Service"
4. Select your repository
5. Configure:
   - **Build Command:** `composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
   - **Environment:** PHP
6. Add environment variables (see below)
7. Deploy!

---

### 🥈 Option 2: Railway
**Best for:** More resources, $5 free credit monthly

**Free Tier Includes:**
- $5 credit per month (usually enough for small apps)
- 512 MB RAM
- Automatic deployments
- PostgreSQL database

**Deployment Steps:**
1. Sign up at [railway.app](https://railway.app)
2. Connect GitHub
3. Create new project → Deploy from GitHub
4. Select your repository
5. Railway auto-detects Laravel
6. Add environment variables
7. Deploy!

---

### 🥉 Option 3: Fly.io
**Best for:** Global edge deployment, good performance

**Free Tier Includes:**
- 3 shared-cpu VMs
- 3GB persistent volume storage
- 160GB outbound data transfer

**Deployment Steps:**
1. Install Fly CLI: `iwr https://fly.io/install.ps1 -useb | iex`
2. Sign up: `fly auth signup`
3. Initialize: `fly launch`
4. Deploy: `fly deploy`

---

## Required Environment Variables

You'll need to set these in your hosting platform:

```env
APP_NAME="Laravel Image Editor"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

SESSION_DRIVER=database
SESSION_LIFETIME=120

FILESYSTEM_DISK=public
```

**Important:** Generate a new APP_KEY:
```bash
php artisan key:generate --show
```

---

## Pre-Deployment Checklist

### 1. Update .env for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-deployed-url.com
```

### 2. Optimize for Production
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### 3. Database Setup
- Most free hosts provide PostgreSQL (free tier)
- You may need to migrate your database
- Update `DB_CONNECTION=pgsql` in .env

### 4. Storage Configuration
- Free hosts usually have ephemeral storage (files deleted on restart)
- Consider using cloud storage (AWS S3, Cloudinary) for images
- Or use the host's persistent storage if available

---

## Render-Specific Setup

### Create `render.yaml` (Optional)
```yaml
services:
  - type: web
    name: laravel-image-editor
    env: php
    buildCommand: composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: LOG_LEVEL
        value: error
```

### Required Buildpacks
Render auto-detects PHP, but you may need:
- PHP 8.1+ (specify in `composer.json` - already done ✅)
- GD extension for image processing

### Storage Considerations
Since Render has ephemeral storage, you have two options:

**Option A: Use Cloud Storage (Recommended)**
- AWS S3 (free tier: 5GB for 12 months)
- Cloudinary (free tier: 25GB)
- Update `config/filesystems.php` to use S3

**Option B: Use Render Disk (Paid)**
- Requires paid plan for persistent storage

---

## Railway-Specific Setup

### Create `Procfile` (Optional)
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

### Database Setup
1. Add PostgreSQL service in Railway
2. Railway provides connection string automatically
3. Update `.env` with Railway's database credentials

---

## Important Notes

### ⚠️ Free Tier Limitations

1. **Cold Starts:** Free tiers may "sleep" after inactivity
   - First request after sleep takes 30-60 seconds
   - Render: 15 minutes inactivity = sleep
   - Railway: Sleeps after inactivity

2. **Storage:**
   - Most free tiers have **ephemeral storage** (files deleted on restart)
   - **Solution:** Use cloud storage (S3, Cloudinary) for uploaded images

3. **Database:**
   - Free PostgreSQL databases available
   - May need to migrate from MySQL to PostgreSQL

4. **Resource Limits:**
   - Limited RAM (512MB)
   - Limited CPU
   - May be slow under load

### 🔧 Required Changes for Cloud Storage

If using cloud storage (recommended for free hosting):

1. **Install AWS SDK:**
   ```bash
   composer require league/flysystem-aws-s3-v3
   ```

2. **Update `config/filesystems.php`:**
   ```php
   's3' => [
       'driver' => 's3',
       'key' => env('AWS_ACCESS_KEY_ID'),
       'secret' => env('AWS_SECRET_ACCESS_KEY'),
       'region' => env('AWS_DEFAULT_REGION'),
       'bucket' => env('AWS_BUCKET'),
       'url' => env('AWS_URL'),
   ],
   ```

3. **Update `.env`:**
   ```env
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your-key
   AWS_SECRET_ACCESS_KEY=your-secret
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your-bucket-name
   ```

---

## Step-by-Step: Deploy to Render

1. **Prepare Your Code:**
   ```bash
   # Commit all changes
   git add .
   git commit -m "Prepare for deployment"
   git push origin main
   ```

2. **Sign Up at Render:**
   - Go to [render.com](https://render.com)
   - Sign up with GitHub

3. **Create Web Service:**
   - Click "New +" → "Web Service"
   - Connect your GitHub repository
   - Select your repository

4. **Configure Service:**
   - **Name:** laravel-image-editor (or your choice)
   - **Environment:** PHP
   - **Region:** Choose closest to you
   - **Branch:** main (or your default branch)
   - **Root Directory:** (leave empty)
   - **Build Command:**
     ```
     composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
     ```
   - **Start Command:**
     ```
     php artisan serve --host=0.0.0.0 --port=$PORT
     ```

5. **Add Environment Variables:**
   - Click "Environment" tab
   - Add all variables from the list above
   - Generate APP_KEY: `php artisan key:generate --show`

6. **Add PostgreSQL Database:**
   - Click "New +" → "PostgreSQL"
   - Create database
   - Copy connection string
   - Update DB_* variables in your web service

7. **Deploy:**
   - Click "Create Web Service"
   - Wait for deployment (5-10 minutes)
   - Your app will be live at: `https://your-app-name.onrender.com`

8. **Run Migrations:**
   - Use Render's shell or SSH
   - Run: `php artisan migrate`

---

## Troubleshooting

### Images Not Uploading
- Check storage permissions
- Verify FILESYSTEM_DISK is set correctly
- Consider using cloud storage

### Database Connection Errors
- Verify database credentials
- Check if database is accessible
- Run migrations: `php artisan migrate`

### 500 Errors
- Check logs in hosting dashboard
- Verify APP_KEY is set
- Check APP_DEBUG=false in production

### Slow Performance
- Free tiers have limited resources
- Consider upgrading or optimizing code
- Use caching: `php artisan config:cache`

---

## Alternative: Keep Using Ngrok (Free)

If you want to keep your current setup:
- Ngrok is free for basic use
- Your app runs locally
- Ngrok provides public URL
- No deployment needed
- **Limitation:** Your computer must be running

---

## Recommended: Render (Easiest)

**Why Render:**
- ✅ Easiest setup
- ✅ Automatic SSL
- ✅ Free PostgreSQL
- ✅ GitHub integration
- ✅ Good documentation

**Get Started:**
1. Sign up: [render.com](https://render.com)
2. Follow steps above
3. Deploy in 10 minutes!

---

## Cost Comparison

| Platform | Free Tier | Paid Starts At |
|----------|-----------|----------------|
| Render | 750 hrs/month | $7/month |
| Railway | $5 credit/month | ~$5/month |
| Fly.io | 3 VMs | ~$2/month |
| Ngrok | Basic free | $8/month |

**Recommendation:** Start with Render's free tier, upgrade if needed.

