FROM rafapaulin/php-fpm-dev:latest

#### Packages ####
RUN apk update && apk upgrade
RUN apk --update --no-cache add \
        curl \
        git \
        shadow \
        zsh \
        nodejs-npm
#### End of Packages ####

RUN git clone --depth 1 https://github.com/robbyrussell/oh-my-zsh.git /root/apps/oh-my-zsh \
        && echo 'export ZSH=$HOME/apps/oh-my-zsh' >> /root/.zshrc \
        && echo 'ZSH_THEME="robbyrussell"' >> /root/.zshrc \
        && echo 'plugins=()' >> /root/.zshrc \
        && echo 'source $ZSH/oh-my-zsh.sh' >> /root/.zshrc \
        && echo 'export TERM=xterm-256color' >> /root/.zshrc \
        && echo 'source $HOME/.aliases' >> /root/.zshrc

#### Composer ####
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
VOLUME /root/.composer/cache
#### End of Composer ####

#### Define ZSH as default bash ####
ENV SHELL=/bin/zsh
RUN chsh -s /bin/zsh
#### End of Define ZSH as default bash ####

#### Other workstation configs ####
COPY ./general/.aliases /root/.aliases
RUN mkdir -p /var/www/resources
COPY ./wordpress/ /var/www/resources
#### End of Other workstation configs ####

WORKDIR /var/www/html

CMD ["php-fpm7", "--allow-to-run-as-root", "-F"]
