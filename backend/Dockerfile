FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

FROM php:8.2-fpm AS app

WORKDIR /var/www/html

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends libicu-dev libonig-dev; \
    docker-php-ext-install mbstring; \
    docker-php-ext-install intl || true; \
    rm -rf /var/lib/apt/lists/*

COPY . /var/www/html

COPY --from=vendor /app/vendor /var/www/html/vendor

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 \
    PHP_MEMORY_LIMIT=256M

EXPOSE 9000


