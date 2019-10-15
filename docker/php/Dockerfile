FROM php:7.2.23-fpm-alpine3.10

ENV REDIS_VERSION 5.0.2

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

WORKDIR /var/ricochetuniverse