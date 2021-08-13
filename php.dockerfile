FROM php:7.4-cli-alpine

RUN docker-php-source extract && \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    docker-php-source delete

COPY docker/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN mkdir -p /azure-service-bus

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /azure-service-bus
