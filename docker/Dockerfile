FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev && \
    docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ОБЯЗАТЕЛЬНО ВЫПОЛНИТЬ В КОНТЕЙНЕРЕ LARAVEL

# chown -R www-data:www-data /var/www/html/storage &&
# chown -R www-data:www-data /var/www/html/bootstrap/cache

# RUN composer install