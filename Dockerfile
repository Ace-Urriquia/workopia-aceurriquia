# =========================
# FRONTEND BUILD
# =========================
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# =========================
# PHP / LARAVEL
# =========================
FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Vite production files
COPY --from=frontend /app/public/build /var/www/html/public/build

# Prevent the image startup script from running Composer again
ENV SKIP_COMPOSER=1

# Web root
ENV WEBROOT=/var/www/html/public

ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/bin/sh", "-c", "php artisan migrate --force && /start.sh"]