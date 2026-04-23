FROM node:22-alpine AS frontend

RUN apk add --no-cache \
      php \
      php-cli \
      php-fpm \
      php-json \
      php-mbstring \
      php-xml \
      php-curl

WORKDIR /app

COPY package*.json ./
RUN npm ci --frozen-lockfile

COPY . .
RUN npm run build

FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
      --optimize-autoloader \
      --no-dev \
      --no-scripts \
      --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev

FROM php:8.4-fpm AS production

RUN apt-get update && apt-get install -y --no-install-recommends \
      curl libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip \
    && docker-php-ext-install mbstring exif pcntl bcmath gd pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p storage/logs \
              storage/framework/cache \
              storage/framework/sessions \
              storage/framework/views \
              bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache \
    && chmod +x start-laravel.sh

EXPOSE 9000

ENTRYPOINT ["./start-laravel.sh"]
