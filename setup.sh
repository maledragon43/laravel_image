#!/bin/bash

echo "Setting up Laravel Image Editor..."
echo

echo "Installing Composer dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "Error installing dependencies!"
    exit 1
fi

echo
echo "Creating .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created."
else
    echo ".env file already exists."
fi

echo
echo "Generating application key..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo "Error generating key!"
    exit 1
fi

echo
echo "Creating storage directories..."
mkdir -p storage/app/public/images/originals
mkdir -p storage/app/public/images/working
mkdir -p storage/app/public/images/history
mkdir -p storage/app/public/images/final

echo
echo "Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo
echo "Creating storage link..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo "Warning: Storage link creation failed. You may need to run this manually."
fi

echo
echo "Setup complete!"
echo
echo "To start the server, run: php artisan serve"
echo "Then open http://localhost:8000 in your browser"
echo

