FROM php:7.1.8-apache

MAINTAINER Mahfuz Shaikh

COPY . /srv/app
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/tenant.conf /etc/apache2/sites-enabled/tenant.conf

RUN docker-php-ext-install mbstring pdo pdo_mysql \
    && chown -R www-data:www-data /srv/app \
    && a2enmod rewrite \
    && a2enmod headers

WORKDIR /srv/app