FROM php:8.4.5-fpm-alpine

RUN apk update && apk upgrade && apk add --no-cache git

# Install extensions
COPY --from=mlocati/php-extension-installer:2.7.27 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl pcntl pdo_mysql redis-6.1.0 xdebug

# Install Composer
COPY --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer

COPY extra.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/ricochetuniverse
