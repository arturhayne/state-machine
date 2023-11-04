FROM php:8.1-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer