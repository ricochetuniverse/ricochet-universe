FROM php:7.2.23-fpm-alpine3.10
RUN apk update && apk upgrade && \
    apk add --no-cache git
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/ricochetuniverse
