FROM php:8.3.19-fpm-alpine

RUN apk update && apk upgrade && apk add --no-cache git

# Install extensions
COPY --from=mlocati/php-extension-installer:2.7.27 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql redis-6.1.0 xdebug

# Install Composer
COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer

COPY extra.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/ricochetuniverse
