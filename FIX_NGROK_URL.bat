@echo off
echo ========================================
echo Fix Ngrok URL Configuration
echo ========================================
echo.
echo This script will help fix the APP_URL issue.
echo.
echo Step 1: Updating .env file...
echo.

REM Check if .env exists
if not exist ".env" (
    echo ERROR: .env file not found!
    echo Please create .env file first.
    pause
    exit /b 1
)

REM Create backup
copy .env .env.backup >nul 2>&1
echo Created backup: .env.backup

REM Update APP_URL (this is a template - user needs to manually edit)
echo.
echo ========================================
echo IMPORTANT: Manual Step Required
echo ========================================
echo.
echo Please open .env file and change:
echo   APP_URL=http://localhost:8000
echo.
echo To:
echo   APP_URL=https://nicolas-pseudohistoric-credulously.ngrok-free.dev
echo.
echo (Make sure to use https:// and NO trailing slash)
echo.
pause

echo.
echo Step 2: Clearing configuration cache...
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear
echo.
echo Configuration cache cleared!
echo.
echo Step 3: Verifying APP_URL...
D:\xampp\php\php.exe artisan tinker --execute="echo config('app.url');"
echo.
echo ========================================
echo Next Steps:
echo ========================================
echo 1. Make sure Laravel server is running:
echo    D:\xampp\php\php.exe artisan serve --host=0.0.0.0
echo.
echo 2. Make sure ngrok is running:
echo    ngrok http 8000
echo.
echo 3. Test your site:
echo    https://nicolas-pseudohistoric-credulously.ngrok-free.dev
echo.
echo 4. Clear browser cache (Ctrl+Shift+Delete) or hard refresh (Ctrl+F5)
echo.
pause

