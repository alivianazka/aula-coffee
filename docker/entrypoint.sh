#!/bin/sh
set -e

# Cache configuration & route
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

# Jalankan supervisord (menjalankan php-fpm dan nginx bersamaan)
exec /usr/bin/supervisord -c /etc/supervisord.conf
