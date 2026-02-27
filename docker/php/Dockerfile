FROM php:8.4-fpm

# 1. System Dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    netcat-openbsd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. PHP Extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 4. FAST CACHING: Install dependencies first
# This stays cached unless composer.json changes
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction

# 5. Copy App Code
COPY . .

# 6. FIX THE SLOWDOWN: 
# Only create directories and set permissions on internal folders.
# Do NOT chown the entire /var/www if you are using bind mounts.
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# 7. Finalize
RUN git config --global --add safe.directory /var/www && \
    composer dump-autoload --optimize

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
EXPOSE 9000
CMD ["php-fpm"]