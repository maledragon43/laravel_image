# Quick Guide: Deploy to Render (Free)

## Prerequisites
- GitHub account
- Your code pushed to GitHub
- 10 minutes

## Step 1: Push to GitHub

If you haven't already:

```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

## Step 2: Sign Up at Render

1. Go to [render.com](https://render.com)
2. Click "Get Started for Free"
3. Sign up with GitHub (easiest)

## Step 3: Create Web Service

1. Click **"New +"** → **"Web Service"**
2. Connect your GitHub account (if not already)
3. Select your repository: `laravel_image` (or your repo name)
4. Click **"Connect"**

## Step 4: Configure Service

Fill in these settings:

- **Name:** `laravel-image-editor` (or your choice)
- **Environment:** `PHP`
- **Region:** Choose closest to you
- **Branch:** `main` (or your default branch)
- **Root Directory:** (leave empty)
- **Build Command:**
  ```
  composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
  ```
- **Start Command:**
  ```
  php artisan serve --host=0.0.0.0 --port=$PORT
  ```

## Step 5: Add Environment Variables

Click **"Environment"** tab and add:

```
APP_NAME=Laravel Image Editor
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
LOG_CHANNEL=stack
LOG_LEVEL=error
SESSION_DRIVER=database
SESSION_LIFETIME=120
FILESYSTEM_DISK=public
```

**Important:** Generate APP_KEY first:
```bash
php artisan key:generate --show
```
Copy the output and add:
```
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
```

## Step 6: Add PostgreSQL Database

1. Click **"New +"** → **"PostgreSQL"**
2. Name: `laravel-image-editor-db`
3. Plan: **Free**
4. Click **"Create Database"**
5. Wait for it to create (1-2 minutes)
6. Copy the **Internal Database URL**
7. Go back to your Web Service → Environment
8. Add these variables (parse from the URL):
   ```
   DB_CONNECTION=pgsql
   DB_HOST=your-host-from-url
   DB_PORT=5432
   DB_DATABASE=your-database-name
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   ```

## Step 7: Deploy!

1. Click **"Create Web Service"**
2. Wait for deployment (5-10 minutes)
3. Your app will be live at: `https://your-app-name.onrender.com`

## Step 8: Run Migrations

1. Go to your Web Service
2. Click **"Shell"** tab
3. Run:
   ```bash
   php artisan migrate
   ```

## Step 9: Create Storage Link

In the Shell:
```bash
php artisan storage:link
```

## Step 10: Test Your App!

Visit your URL and test:
- Upload an image
- Edit (rotate/crop)
- Save image
- Delete image

---

## Important Notes

### ⚠️ Storage Warning
Render's free tier has **ephemeral storage** - files are deleted when the service restarts.

**Solutions:**
1. **Use Cloud Storage (Recommended):**
   - AWS S3 (free tier: 5GB for 12 months)
   - Cloudinary (free tier: 25GB)
   - See `FREE_DEPLOYMENT_GUIDE.md` for setup

2. **Accept Limitations:**
   - Images will be lost on restart
   - Fine for testing/demos

### 🐌 Cold Starts
- Free tier "sleeps" after 15 minutes of inactivity
- First request after sleep takes 30-60 seconds
- Subsequent requests are fast

### 💾 Database
- PostgreSQL is provided free
- Data persists (unlike storage)
- You may need to migrate from MySQL

---

## Troubleshooting

### Build Fails
- Check build logs in Render dashboard
- Verify PHP version (needs 8.1+)
- Check composer.json is correct

### 500 Error
- Check logs: Render Dashboard → Logs
- Verify APP_KEY is set
- Check database connection

### Images Not Working
- Storage is ephemeral (deleted on restart)
- Use cloud storage for persistence
- Or accept that images reset on restart

### Database Connection Error
- Verify DB_* variables are correct
- Check database is running
- Run migrations: `php artisan migrate`

---

## Next Steps

1. ✅ Your app is deployed!
2. 🔒 Consider adding custom domain (free SSL included)
3. ☁️ Set up cloud storage for persistent images
4. 📊 Monitor usage in Render dashboard

---

## Cost

**Free Tier Includes:**
- 750 hours/month (enough for 24/7)
- 512 MB RAM
- PostgreSQL database
- Automatic SSL
- Custom domains

**Upgrade When:**
- You need more resources
- You need persistent storage
- You want faster cold starts

**Paid Plans Start At:** $7/month

---

## Support

- Render Docs: https://render.com/docs
- Laravel Docs: https://laravel.com/docs
- Render Community: https://community.render.com

