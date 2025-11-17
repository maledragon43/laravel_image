@echo off
echo ========================================
echo Ngrok Configuration Update
echo ========================================
echo.
echo This script will help you update your Laravel app for ngrok.
echo.
echo IMPORTANT: You need to manually edit .env file first!
echo.
echo 1. Open .env file in a text editor
echo 2. Find the line: APP_URL=http://localhost:8000
echo 3. Change it to: APP_URL=https://nicolas-pseudohistoric-credulously.ngrok-free.dev
echo 4. Save the file
echo.
pause
echo.
echo Clearing configuration cache...
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear
echo.
echo Configuration cache cleared!
echo.
echo Verifying storage link...
if exist "public\storage" (
    echo Storage link exists - OK
) else (
    echo Creating storage link...
    D:\xampp\php\php.exe artisan storage:link
)
echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Make sure ngrok is running: ngrok http 8000
echo 2. Restart Laravel server with: D:\xampp\php\php.exe artisan serve --host=0.0.0.0
echo 3. Test your site at: https://nicolas-pseudohistoric-credulously.ngrok-free.dev
echo.
pause

