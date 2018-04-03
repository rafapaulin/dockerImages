FROM alpine:latest

#### Packages ####
RUN apk update && apk upgrade
RUN apk add --update \
	openrc --no-cache \
	curl \
	php7 \
	php7-fpm \
	php7-json \
	php7-phar \
	php7-mbstring \
	php7-openssl \
	php7-zlib \
	php7-gd \
	php7-curl \
	php7-zip \
	php7-dom \
	php7-tokenizer \
	php7-xml \
	php7-xmlwriter \
	nodejs \
	nginx
#### End of Packages ####

#### User/groups ####
RUN set -x ; \
	addgroup -g 82 -S www-data ; \
	adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1
#### End of User/groups ####

#### Composer ####
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
# RUN php composer-setup.php
# RUN php -r "unlink('composer-setup.php');"
# RUN ln -s /composer.phar /bin/composer
#### End of Composer ####

#### Laravel ####
RUN composer global require "laravel/installer"
RUN ln -s $HOME/.composer/vendor/bin/laravel /bin/laravel
RUN cd / \
	laravel new www
#### End of Laravel ####

#### Server config ####
RUN mkdir -p /run/nginx
RUN mkdir /www && \
	chown -R www-data:www-data /var/lib/nginx && \
	chown -R www-data:www-data /www
RUN rc-update add nginx default
RUN rc-update add php-fpm7 default
RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.bkp

COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/mime.types /etc/nginx/mime.types
COPY nginx/ssl.cnf /etc/nginx/ssl/ssl.cnf
COPY nginx/ssl.crt /etc/nginx/ssl/ssl.crt
COPY nginx/ssl.csr /etc/nginx/ssl/ssl.csr
COPY nginx/ssl.key /etc/nginx/ssl/ssl.key
#### End of Server config ####

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]