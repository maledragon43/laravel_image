@echo off
echo Setting up Laravel Image Editor...
echo.

echo Installing Composer dependencies...
call composer install
if errorlevel 1 (
    echo Error installing dependencies!
    pause
    exit /b 1
)

echo.
echo Creating .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created.
) else (
    echo .env file already exists.
)

echo.
echo Generating application key...
call php artisan key:generate
if errorlevel 1 (
    echo Error generating key!
    pause
    exit /b 1
)

echo.
echo Creating storage directories...
if not exist "storage\app\public\images\originals" mkdir "storage\app\public\images\originals"
if not exist "storage\app\public\images\working" mkdir "storage\app\public\images\working"
if not exist "storage\app\public\images\history" mkdir "storage\app\public\images\history"
if not exist "storage\app\public\images\final" mkdir "storage\app\public\images\final"

echo.
echo Creating storage link...
call php artisan storage:link
if errorlevel 1 (
    echo Warning: Storage link creation failed. You may need to run this manually.
)

echo.
echo Setup complete!
echo.
echo To start the server, run: php artisan serve
echo Then open http://localhost:8000 in your browser
echo.
pause

