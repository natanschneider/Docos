#!/bin/bash

php artisan migrate --force
php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache

echo "Laravel app started"

# Keep the container running
tail -f /dev/null