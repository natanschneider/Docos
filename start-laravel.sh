#!/bin/bash

cd /var/www/html

sed -i 's/listen = 127.0.0.1:9000/listen = 127.0.0.1:9002/' /usr/local/etc/php-fpm.d/www.conf

php artisan migrate --force
php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache

exec php-fpm