FROM php:7.2.15-fpm-alpine3.9
RUN apk update && apk upgrade && \
    apk add --no-cache git
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/ricochetuniverse
