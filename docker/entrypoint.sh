#!/bin/sh

# Buat direktori storage yang diperlukan jika belum ada
mkdir -p /var/www/html/storage/logs /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/framework/cache
touch /var/www/html/storage/logs/laravel.log

# Set permission awal
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Buat symbolic link storage jika belum ada
php artisan storage:link || true

# Jalankan migrasi dan seeding database otomatis saat kontainer start
echo "Running database migration and seed..."
php artisan migrate --force || true
php artisan db:seed --force || true

# Cache konfigurasi, route, dan view
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Set permission kembali agar file cache/log yang dibuat oleh root bisa ditulis oleh www-data
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Jalankan supervisord (menjalankan php-fpm, nginx, dan sshd bersamaan)
exec /usr/bin/supervisord -c /etc/supervisord.conf
