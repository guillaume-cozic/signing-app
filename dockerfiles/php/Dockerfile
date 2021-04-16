FROM php:8.0-fpm
RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        npm

RUN npm install -g laravel-echo-server

RUN apt-get update && apt-get install -y \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd


