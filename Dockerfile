FROM php:8.0-fpm

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www
RUN chmod 755 /var/www
