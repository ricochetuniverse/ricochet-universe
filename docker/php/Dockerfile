FROM php:8.3.12-fpm-alpine

ENV REDIS_VERSION 5.3.7

RUN apk update && apk upgrade && \
    apk add --no-cache git && \
    docker-php-ext-install pdo_mysql && \
    # https://github.com/docker-library/php/issues/263#issuecomment-391252514
    curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/$REDIS_VERSION.tar.gz && \
    tar xfz /tmp/redis.tar.gz && \
    rm -r /tmp/redis.tar.gz && \
    mkdir -p /usr/src/php/ext && \
    mv phpredis-* /usr/src/php/ext/redis && \
    docker-php-ext-install redis

COPY extra.ini $PHP_INI_DIR/conf.d/

COPY --from=composer:2.7.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/ricochetuniverse
