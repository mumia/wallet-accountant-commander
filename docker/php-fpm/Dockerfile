FROM php:7.2-fpm-alpine

RUN apk update && apk upgrade && apk add --no-cache bash git openssh unzip zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer --version

RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative \
    && composer clear-cache

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Lisbon /etc/localtime
RUN "date"

# Type docker-php-ext-install to see available extensions
RUN apk update \
    && apk add --no-cache libcurl curl g++ make autoconf openssl-dev

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        libintl \
        icu \
        icu-dev \
        zlib-dev \
        libxml2-dev \
        && docker-php-ext-install intl soap pdo_mysql

RUN pecl install mongodb

# http://symfony.com/doc/current/performance.html
RUN pecl install apcu-5.1.5
RUN docker-php-ext-enable opcache apcu mongodb
# opcache
RUN echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
RUN echo "realpath_cache_ttl=600" >> /usr/local/etc/php/conf.d/php.ini

# phpunit
RUN wget https://phar.phpunit.de/phpunit.phar
RUN chmod +x phpunit.phar
RUN mv phpunit.phar /usr/local/bin/phpunit

# mariadb client
RUN apk add mysql-client
#RUN sed '/skip-external-locking/a bind-address=0.0.0.0' /etc/mysql/my.cnf
#
#RUN mysql_install_db --user=mysql --ldata=/var/lib/mysql

WORKDIR /srv/walletaccountantcommander

COPY start.sh /usr/local/bin/docker-app-start
COPY boot_db.sh /root/boot_db.sh
COPY install_db.sql /root/install_db.sql
RUN chmod +x /root/boot_db.sh
RUN chmod +x /usr/local/bin/docker-app-start

CMD ["docker-app-start"]
