#!/bin/bash
set -e

cd /var/www
env >> /var/www/.env
php artisan clear-compiled
php artisan config:clear
php artisan storage:link

# Run the scheduler in the background
while true; do
    php artisan schedule:run >> /dev/null 2>&1
    sleep 60
done &

php-fpm8.3 -D
nginx -g "daemon off;"