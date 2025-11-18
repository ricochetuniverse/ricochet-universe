FROM php:8.4.14-fpm-alpine

RUN apk update && apk upgrade && apk add --no-cache git

# Install extensions
COPY --from=mlocati/php-extension-installer:2.9.18 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl pcntl pdo_mysql redis xdebug

# Install Composer
COPY --from=composer:2.9.1 /usr/bin/composer /usr/bin/composer

COPY extra.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/ricochetuniverse
