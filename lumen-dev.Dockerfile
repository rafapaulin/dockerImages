FROM rafapaulin/php-fpm-dev:latest

#### Packages ####
RUN apk update && apk upgrade
RUN apk --update --no-cache add git
#### End of Packages ####

#### Composer ####
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
VOLUME /root/.composer/cache
#### End of Composer ####

#### Lumen ####
RUN composer global require "laravel/lumen-installer"
RUN ln -s $HOME/.composer/vendor/bin/lumen /bin/lumen
#### End of Lumen ####

#### Other workstation configs ####
COPY ./laravel-lumen/.aliases /root/.aliases
RUN source /root/.aliases
#### End of Other workstation configs ####


CMD ["php-fpm"]
