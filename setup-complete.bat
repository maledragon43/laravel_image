@echo off
echo ========================================
echo Complete Laravel Setup
echo ========================================
echo.

REM Try to find PHP
set PHP_PATH=
if exist "D:\xampp\php\php.exe" set PHP_PATH=D:\xampp\php\php.exe
if exist "C:\xampp\php\php.exe" set PHP_PATH=C:\xampp\php\php.exe

if "%PHP_PATH%"=="" (
    echo ERROR: PHP not found!
    echo Please ensure XAMPP is installed.
    pause
    exit /b 1
)

echo Using PHP at: %PHP_PATH%
echo.

REM Step 1: Install Composer dependencies
echo [1/5] Installing Composer dependencies...
if exist "composer.phar" (
    %PHP_PATH% composer.phar install
) else (
    echo Composer not found. Please run install-composer.bat first!
    pause
    exit /b 1
)
if errorlevel 1 (
    echo ERROR: Failed to install dependencies!
    pause
    exit /b 1
)

REM Step 2: Create .env file
echo.
echo [2/5] Checking .env file...
if not exist ".env" (
    echo Creating .env file...
    (
        echo APP_NAME=Laravel
        echo APP_ENV=local
        echo APP_KEY=
        echo APP_DEBUG=true
        echo APP_URL=http://localhost
        echo.
        echo LOG_CHANNEL=stack
        echo LOG_LEVEL=debug
        echo.
        echo DB_CONNECTION=mysql
        echo DB_HOST=127.0.0.1
        echo DB_PORT=3306
        echo DB_DATABASE=laravel_image
        echo DB_USERNAME=root
        echo DB_PASSWORD=
        echo.
        echo CACHE_DRIVER=file
        echo FILESYSTEM_DISK=local
        echo QUEUE_CONNECTION=sync
        echo SESSION_DRIVER=file
        echo SESSION_LIFETIME=120
    ) > .env
    echo .env file created!
) else (
    echo .env file already exists.
)

REM Step 3: Generate application key
echo.
echo [3/5] Generating application key...
%PHP_PATH% artisan key:generate
if errorlevel 1 (
    echo WARNING: Failed to generate key. You may need to run this manually.
)

REM Step 4: Create storage directories
echo.
echo [4/5] Creating storage directories...
if not exist "storage\app\public\images\originals" mkdir "storage\app\public\images\originals"
if not exist "storage\app\public\images\working" mkdir "storage\app\public\images\working"
if not exist "storage\app\public\images\history" mkdir "storage\app\public\images\history"
if not exist "storage\app\public\images\final" mkdir "storage\app\public\images\final"
echo Storage directories created!

REM Step 5: Create storage link
echo.
echo [5/5] Creating storage link...
%PHP_PATH% artisan storage:link
if errorlevel 1 (
    echo WARNING: Storage link creation failed. You may need to run this manually.
)

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo To start the server, run:
echo %PHP_PATH% artisan serve
echo.
echo Then open: http://localhost:8000
echo.
pause

