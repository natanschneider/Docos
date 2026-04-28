FROM php:8.4-fpm

COPY . .

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip \
    && docker-php-ext-install mbstring exif pcntl bcmath gd pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

RUN curl -fsSL https://nodejs.org/dist/v24.0.0/node-v24.0.0-linux-x64.tar.xz \
    | tar -xJ -C /usr/local --strip-components=1

COPY package*.json ./
RUN npm ci --frozen-lockfile

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install \
    --optimize-autoloader

COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN npm run build:ssr

RUN php artisan migrate --force \
    && php artisan storage:link --force 2>/dev/null || true \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000

COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["php-fpm", "-F", "-O"]