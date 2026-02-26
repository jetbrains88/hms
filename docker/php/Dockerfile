FROM php:8.4-fpm

# 1. Install system dependencies (Rarely changes)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions (Rarely changes)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www

# 5. Leverage Layer Caching for PHP Dependencies
# By copying only composer files first, we only re-run 'composer install'
# if your dependencies actually change.
COPY ../../composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction

# 6. Copy application code
# This layer changes often, but it's now at the end of the file.
COPY ../.. /var/www

# Copy the entrypoint script
COPY ../../entrypoint.sh /usr/local/bin/entrypoint.sh

# Make the script executable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set the entrypoint
ENTRYPOINT ["entrypoint.sh"]

EXPOSE 9000
#CMD ["php-fpm"]

# 7. Finalize Composer and Permissions
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
