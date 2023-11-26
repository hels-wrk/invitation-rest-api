FROM php:7.4-fpm

MAINTAINER Stefanie Drost <contact@stefaniedrost.com>

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y --no-install-recommends git

RUN usermod -u 1000 www-data

RUN apt-get update && \
    apt-get install -y \
        build-essential \
        libssl-dev \
        zlib1g-dev \
        libzip-dev \
        libwebp-dev \
        zip \
        unzip \
        libpng-dev \
        libjpeg-dev \
        exiftool \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libicu-dev \
        libpq-dev \
        libxpm-dev \
        libvpx-dev \
        mariadb-client \
        libxml2-dev \
        wget

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN docker-php-ext-install -j$(nproc) \
   exif \
   bcmath \
   intl \
   pcntl \
   mysqli \
   pdo \
   pdo_mysql \
   pdo_pgsql \
   soap \
   opcache

EXPOSE 9000

CMD ["php-fpm"]