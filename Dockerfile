FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev g++ git libicu-dev zip libzip-dev unzip libpq-dev \
    && docker-php-ext-install intl opcache pdo pdo_pgsql \
    && pecl install apcu xdebug \
    && docker-php-ext-enable apcu xdebug \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

COPY ./php/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/app