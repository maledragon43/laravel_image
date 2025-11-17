@echo off
echo ========================================
echo Installing Composer Dependencies
echo ========================================
echo.

REM Try to find PHP
set PHP_PATH=
if exist "D:\xampp\php\php.exe" set PHP_PATH=D:\xampp\php\php.exe
if exist "C:\xampp\php\php.exe" set PHP_PATH=C:\xampp\php\php.exe

if "%PHP_PATH%"=="" (
    echo ERROR: PHP not found!
    echo Please ensure XAMPP is installed and PHP is available.
    echo.
    echo You can:
    echo 1. Add PHP to your system PATH, OR
    echo 2. Install Composer from: https://getcomposer.org/download/
    echo.
    pause
    exit /b 1
)

echo Found PHP at: %PHP_PATH%
echo.

REM Check if composer.phar exists
if exist "composer.phar" (
    echo Using existing composer.phar...
    echo.
    echo Installing dependencies...
    %PHP_PATH% composer.phar install
    if errorlevel 1 (
        echo.
        echo ERROR: Failed to install dependencies!
        pause
        exit /b 1
    )
) else (
    echo Composer not found. Downloading composer.phar...
    echo.
    %PHP_PATH% -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    %PHP_PATH% composer-setup.php
    %PHP_PATH% -r "unlink('composer-setup.php');"
    
    if exist "composer.phar" (
        echo.
        echo Composer downloaded successfully!
        echo Installing dependencies...
        %PHP_PATH% composer.phar install
        if errorlevel 1 (
            echo.
            echo ERROR: Failed to install dependencies!
            pause
            exit /b 1
        )
    ) else (
        echo.
        echo ERROR: Failed to download Composer!
        echo Please download manually from: https://getcomposer.org/download/
        pause
        exit /b 1
    )
)

echo.
echo ========================================
echo Dependencies installed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Create .env file (if not exists)
echo 2. Run: %PHP_PATH% artisan key:generate
echo 3. Run: %PHP_PATH% artisan storage:link
echo 4. Run: %PHP_PATH% artisan serve
echo.
pause

