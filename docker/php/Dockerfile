FROM php:fpm

RUN apt-get update && apt-get install -y libmcrypt-dev

RUN apt-get install -y libssl-dev libcurl4-openssl-dev pkg-config

# lumen packages
RUN docker-php-ext-install mcrypt mbstring tokenizer mysqli pdo_mysql

RUN apt-get install -y libz-dev libmemcached-dev

RUN pecl install mongodb && echo extension=mongodb.so >> /usr/local/etc/php/conf.d/mongodb.ini

RUN apt-get install -y curl

RUN curl -s https://getcomposer.org/installer | php

RUN mv composer.phar /usr/local/bin/composer

RUN apt-get install -y git unzip

WORKDIR /app

ENTRYPOINT php-fpm

EXPOSE 9000