FROM rafapaulin/php-fpm-dev:latest

#### Packages ####
RUN apk update && apk upgrade
RUN apk --update --no-cache add \
        curl \
        git \
        shadow \
        zsh
#### End of Packages ####

RUN git clone --depth 1 https://github.com/robbyrussell/oh-my-zsh.git /root/apps/oh-my-zsh \
        && echo 'export ZSH=$HOME/apps/oh-my-zsh' >> /root/.zshrc \
        && echo 'ZSH_THEME="robbyrussell"' >> /root/.zshrc \
        && echo 'plugins=(git)' >> /root/.zshrc \
        && echo 'source $ZSH/oh-my-zsh.sh' >> /root/.zshrc \
        && echo 'export TERM=xterm-256color' >> /root/.zshrc

#### Composer ####
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#### End of Composer ####

#### Lumen ####
RUN composer global require "laravel/lumen-installer"
RUN ln -s $HOME/.composer/vendor/bin/lumen /bin/lumen
#### End of Lumen ####

ENV SHELL=/bin/zsh
RUN chsh -s /bin/zsh

WORKDIR /var/www

CMD ["php-fpm7", "--allow-to-run-as-root", "-F"]