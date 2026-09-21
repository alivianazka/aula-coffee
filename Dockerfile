# Stage 1: Build Frontend (Vite & Tailwind)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application & Server
FROM php:8.2-fpm-alpine

# Install dependencies, PHP extensions, and OpenSSH for Azure App Service SSH
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    mariadb-client \
    ca-certificates \
    openssh \
    && echo "root:Docker!" | chpasswd \
    && ssh-keygen -A

RUN docker-php-ext-install pdo pdo_mysql gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Copy build frontend dari Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies (tanpa dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy konfigurasi Nginx, Supervisor, SSH, Entrypoint, & Sertifikat SSL Azure
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/sshd_config /etc/ssh/sshd_config
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/DigiCertGlobalRootCA.crt.pem /etc/ssl/certs/DigiCertGlobalRootCA.crt.pem

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port 80 untuk Web, Port 2222 untuk Azure SSH
EXPOSE 80 2222

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
