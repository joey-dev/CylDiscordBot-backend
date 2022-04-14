FROM php:8.0-fpm

WORKDIR /api

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN docker-php-ext-install pdo pdo_mysql mysqli

#CMD [ "sh", "-c", "php composer.phar install" ]
#CMD /bin/bash -c 'php composer.phar install; /bin/bash'

#COPY composer.json ./
#COPY composer.lock ./

#RUN php composer.phar install

