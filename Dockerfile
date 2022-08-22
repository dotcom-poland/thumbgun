FROM composer:2 as composer
FROM php:8.1-fpm-alpine as base

# Git
RUN apk add --update --no-cache git curl

# ZIP module
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip

# Imagick module
RUN apk add --no-cache ${PHPIZE_DEPS} libgomp imagemagick imagemagick-dev && \
	pecl install -o -f imagick && \
	docker-php-ext-enable imagick && \
	apk del --no-cache ${PHPIZE_DEPS}

# Symfony CLI tool
RUN apk add --no-cache bash && \
	curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && \
	apk add symfony-cli && \
	apk del bash

# Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# XDebug
RUN apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug-3.1.5 && \
    apk del --no-cache ${PHPIZE_DEPS}

COPY ./artifacts/xdebug /usr/local/bin/xdebug
RUN chmod +x /usr/local/bin/xdebug

# Clean up image
RUN rm -rf /tmp/* /var/cache
