# syntax=docker/dockerfile:1

FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    postgresql-dev \
    mysql-client \
    nodejs \
    npm \
    git \
    curl \
    unzip \
    linux-headers \
    autoconf \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    zip \
    gd \
    intl \
    opcache \
    bcmath \
    pcntl \
    sockets

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure PHP
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx-site.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies (--no-scripts since app code not present yet)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package.json for npm install caching
COPY package.json package-lock.json* ./

# Install Node dependencies
RUN npm install

# Copy application code (needed for artisan commands and wayfinder:generate)
COPY . .

# Generate Wayfinder routes (required before npm run build)
RUN php artisan wayfinder:generate

# Build frontend assets
RUN npm run build

# Run Laravel optimization commands (includes package:discover via post-autoload-dump)
RUN composer run-script post-autoload-dump \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]