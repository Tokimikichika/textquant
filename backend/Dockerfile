FROM php:8.2-fpm

WORKDIR /var/www/html

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends git unzip libicu-dev libonig-dev; \
    docker-php-ext-install mbstring; \
    docker-php-ext-install intl || true; \
    rm -rf /var/lib/apt/lists/*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && php -r "unlink('composer-setup.php');"

COPY . /var/www/html

RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

EXPOSE 9000