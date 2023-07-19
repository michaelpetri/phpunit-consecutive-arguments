ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli-alpine3.16

# Better extension installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Install composer
COPY --from=composer:2.4.2 /usr/bin/composer /usr/bin/composer

# Install development tools
RUN apk add --no-cache \
        bash \
        make \
        unzip \
        git \
 && install-php-extensions \
        xdebug