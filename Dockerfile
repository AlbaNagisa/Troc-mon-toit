FROM php:8.0-apache
#Install additional PHP extensions

RUN docker-php-ext-install pdo pdo_mysql mysqli