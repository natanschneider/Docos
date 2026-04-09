FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install mbstring exif pcntl bcmath gd \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN npm install && npm run build:ssr

RUN chmod +x start-laravel.sh

CMD ["php-fpm"]
EXPOSE 9002