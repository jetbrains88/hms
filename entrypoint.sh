#!/bin/sh
set -e

# FIX PERMISSIONS (Add this block)
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Wait for MySQL to be ready
# We use a simple PHP command to test the connection using the env variables from docker-compose
echo "Waiting for MySQL to start..."

# until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), 'root', getenv('MYSQL_ROOT_PASSWORD')); } catch (Exception \$e) { exit(1); }"; do
#   echo "MySQL is still warming up... sleeping 1s"
#   sleep 1
# done

echo "MySQL is up and running!"

# Run migrations
# echo "Running migrations..."
# php artisan migrate:fresh --seed

# Clear and cache configurations for performance
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache

# Hand off to the main Docker command (php-fpm)
exec "$@"
