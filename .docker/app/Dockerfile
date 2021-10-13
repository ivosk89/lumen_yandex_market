FROM php:8.0.10-fpm

RUN apt-get update && apt-get install -y git zip unzip

COPY --chown=www-data:www-data ./app /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --prefer-dist

EXPOSE 9000