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

# xdebug
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions xdebug
ENV PHP_IDE_CONFIG 'serverName=DANCEOFTHEKNIGHTS'
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey = DANCEOFTHEKNIGHTS" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


#RUN