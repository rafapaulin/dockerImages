FROM alpine:latest

#### Packages ####
RUN apk update && apk upgrade
RUN apk --update --no-cache add \
        php7 \
        php7-dom \
        php7-ctype \
        php7-curl \
        php7-fpm \
        php7-gd \
        php7-iconv \
        php7-intl \
        php7-json \
        php7-mbstring \
        php7-mcrypt \
        php7-opcache \
        php7-openssl \
        php7-pdo \
        php7-pdo_mysql \
        php7-phar \
        php7-posix \
        php7-session \
        php7-xml \
        php7-xmlwriter \
        php7-zip \
        php7-tokenizer \
        php7-zlib
#### End of Packages ####

COPY ./phpfpm/php-fpm.ini /etc/php7/conf.d/50-setting.ini
COPY ./phpfpm/php-fpm.conf /etc/php7/php-fpm.conf

EXPOSE 9000

WORKDIR /var/www

CMD ["php-fpm7", "--allow-to-run-as-root", "-F"]