FROM php:8.1-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y git zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl --silent --show-error https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer