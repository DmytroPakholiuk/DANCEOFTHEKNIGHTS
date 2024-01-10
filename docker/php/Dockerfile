FROM php:8.1-fpm

RUN apt-get update --fix-missing \
        && apt-get install -y --no-install-recommends \
        libpq-dev wget unzip mc git locales iputils-ping \
        postgresql-client ssh-client libssl-dev libzip-dev libmagickwand-dev \
        apt-transport-https ca-certificates libpng-dev libicu-dev libldap2-dev \
        && docker-php-ext-install pdo_pgsql pdo_mysql bcmath sockets gd zip \
        && docker-php-ext-install intl ldap

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


#RUN