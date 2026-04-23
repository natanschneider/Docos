#!/bin/bash
set -e

cd /var/www/html

php artisan migrate --force
php artisan storage:link --force 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php-fpm