FROM php:8.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip \
    && docker-php-ext-install mbstring exif pcntl bcmath gd pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

RUN curl -fsSL https://nodejs.org/dist/v24.0.0/node-v24.0.0-linux-x64.tar.xz \
    | tar -xJ -C /usr/local --strip-components=1

COPY package*.json ./
RUN npm ci --frozen-lockfile

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install \
    --optimize-autoloader \
    --no-dev \
    --no-scripts \
    --ignore-platform-reqs

COPY . .

COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p storage/logs \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache \
    && chmod +x start-laravel.sh

RUN npm run build:ssr

EXPOSE 13714

CMD ["node", "bootstrap/ssr/ssr.js"]
