# syntax=docker/dockerfile:1

FROM php:8.4-fpm-alpine AS base

# Remove base image's default php-fpm.d/docker.conf to avoid conflicting error_log/access.log directives
RUN rm -f /usr/local/etc/php-fpm.d/docker.conf

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

# Create ALL runtime directories EARLY (before config tests)
RUN mkdir -p /var/log/nginx /var/run/nginx /var/lib/nginx/tmp/client_body /var/lib/nginx/tmp/proxy /var/lib/nginx/tmp/fastcgi /var/lib/nginx/tmp/uwsgi /var/lib/nginx/tmp/scgi /var/lib/nginx/logs /var/log/php-fpm \
    && chown -R www-data:www-data /var/log/nginx /var/run/nginx /var/lib/nginx /var/log/php-fpm

# Verify directories exist with correct ownership
RUN echo "===VERIFY RUNTIME DIRECTORIES===" && \
    ls -la /var/log/php-fpm/ && \
    ls -la /var/log/nginx/ && \
    ls -la /var/run/nginx/ && \
    ls -la /var/lib/nginx/

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
COPY docker/php-fpm-global.conf /usr/local/etc/php-fpm.conf

# Debug: show effective php-fpm configs
RUN echo "===GLOBAL===" && cat /usr/local/etc/php-fpm.conf
RUN echo "===POOL===" && cat /usr/local/etc/php-fpm.d/www.conf
RUN echo "===PHPINI===" && cat /usr/local/etc/php/conf.d/app.ini
RUN echo "===LS PHPFPMD===" && ls -la /usr/local/etc/php-fpm.d/

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx-site.conf /etc/nginx/http.d/default.conf
COPY docker/start-nginx.sh /usr/local/bin/start-nginx.sh
RUN chmod +x /usr/local/bin/start-nginx.sh

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
# NOTE: config:cache, route:cache, view:cache, event:cache REMOVED from build step.
# These cache env var values at BUILD TIME, but Railway injects env vars at RUNTIME.
# Running them here bakes stale/missing values into the image.
# Laravel works fine without these caches; they are a perf optimization, not required.
RUN composer run-script post-autoload-dump

# Set permissions for app directories
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# FINAL php-fpm config test - runs AFTER all dirs created, perms set, config copied
RUN echo "===FINAL PHP-FPM CONFIG TEST===" && php-fpm -t

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]