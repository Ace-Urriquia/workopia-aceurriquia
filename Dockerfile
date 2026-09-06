# ==============================
# FRONTEND BUILD
# ==============================

FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# ==============================
# LARAVEL + NGINX
# ==============================

FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

# Copy Laravel application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

# Copy Vite production build
COPY --from=frontend /app/public/build ./public/build

# IMPORTANT: Verify that the Vite files really exist
RUN echo "===== VITE BUILD FILES =====" && \
    find /var/www/html/public/build -type f

# Prevent startup script from running Composer again
ENV SKIP_COMPOSER=1

# Web root
ENV WEBROOT=/var/www/html/public

# PHP / Nginx settings
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Laravel production settings
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/bin/sh", "-c", "php artisan migrate --force && /start.sh"]