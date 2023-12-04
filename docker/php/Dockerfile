FROM php:8.2.13-fpm

RUN apt-get update && apt-get upgrade -y \
    libonig-dev \
    libmcrypt-dev \
    zlib1g-dev \
    libpng-dev \
    libxml2-dev

## ZIP
RUN apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

## PDO
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo pdo_mysql

## LIBS
RUN docker-php-ext-install dom
RUN docker-php-ext-install intl
RUN docker-php-ext-install iconv
RUN docker-php-ext-install opcache
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install gd

## XDEBUG
RUN pecl install xdebug-3.2.0
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.mode=develop,debug,coverage\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_handler=dbgp\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.max_nesting_level=1024\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

## REDIS
RUN pecl install redis
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini
RUN echo "date.timezone=America/Sao_Paulo" > /usr/local/etc/php/conf.d/timezone_sao_paulo.ini
RUN echo "memory_limit = 1024M" > /usr/local/etc/php/conf.d/memory_limit.ini

## COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

## GIT
RUN apt install git -y

## PHP HTTP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && install-php-extensions http

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m
USER dev

EXPOSE 9000
CMD ["php-fpm"]