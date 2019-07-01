FROM php:fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PHP_XDEBUG_DEFAULT_ENABLE ${PHP_XDEBUG_DEFAULT_ENABLE:-1}
ENV PHP_XDEBUG_REMOTE_ENABLE ${PHP_XDEBUG_REMOTE_ENABLE:-1}
ENV PHP_XDEBUG_REMOTE_HOST ${PHP_XDEBUG_REMOTE_HOST:-"127.0.0.1"}
ENV PHP_XDEBUG_REMOTE_PORT ${PHP_XDEBUG_REMOTE_PORT:-9000}
ENV PHP_XDEBUG_REMOTE_AUTO_START ${PHP_XDEBUG_REMOTE_AUTO_START:-1}
ENV PHP_XDEBUG_REMOTE_CONNECT_BACK ${PHP_XDEBUG_REMOTE_CONNECT_BACK:-1}
ENV PHP_XDEBUG_IDEKEY ${PHP_XDEBUG_IDEKEY:-docker}
ENV PHP_XDEBUG_PROFILER_ENABLE ${PHP_XDEBUG_PROFILER_ENABLE:-0}
ENV PHP_XDEBUG_PROFILER_OUTPUT_DIR ${PHP_XDEBUG_PROFILER_OUTPUT_DIR:-"/tmp"}

# #### Packages ####
RUN apk update && apk upgrade &&apk add --no-cache \
        g++ \
        make \
        curl \
        autoconf \
        libzip-dev
RUN docker-php-source extract && docker-php-ext-configure zip
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install \
		mysqli \
                pdo_mysql \
                zip
RUN docker-php-ext-enable \
		mysqli \
                pdo_mysql \
                zip
RUN docker-php-source delete && rm -rf /tmp/*
# #### End of Packages ####

# COPY ./phpfpm/php-fpm.ini /etc/php7/conf.d/50-setting.ini
# COPY ./phpfpm/php-fpm.conf /etc/php7/php-fpm.conf
# COPY ./phpfpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug-dev.ini

EXPOSE 9000

WORKDIR /var/www/html

CMD ["php-fpm"]
