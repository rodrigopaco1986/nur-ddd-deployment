#!/bin/bash

echo "Deploying..."

cd /var/www/nur-ddd-sales-app-dev

git fetch --all
git reset --hard origin/develop

composer install --no-dev --optimize-autoloader

php artisan migrate

php artisan route:clear
php artisan config:clear
php artisan cache:clear

echo "✔ Deployment successful!"