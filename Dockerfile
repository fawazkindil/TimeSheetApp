FROM php:7.4-fpm-alpine

RUN apk update
RUN apk add --no-cache libzip-dev libpng-dev
RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN docker-php-ext-install pdo pdo_mysql zip gd
