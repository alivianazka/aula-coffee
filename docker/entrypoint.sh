#!/bin/sh

# Buat symbolic link storage jika belum ada
php artisan storage:link || true

# Jalankan migrasi database otomatis saat kontainer start
echo "Running database migration..."
php artisan migrate --force || true

# Cache konfigurasi, route, dan view
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Jalankan supervisord (menjalankan php-fpm, nginx, dan sshd bersamaan)
exec /usr/bin/supervisord -c /etc/supervisord.conf
