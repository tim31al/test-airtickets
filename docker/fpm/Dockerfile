FROM php:7.4-fpm

LABEL maintainer="Alexandr Timofeev <tim31al@gmail.com>"

RUN apt-get update && apt-get install -y \
	libpq-dev \
	libicu-dev \
	libzip-dev \
	libcurl4-openssl-dev \
	libssl-dev \
	libpng-dev \
	libmemcached-dev \
	librabbitmq-dev \
	unzip

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
        && docker-php-ext-install intl \
            pdo_pgsql \
            zip \
            json \
            curl \
            gd \
            pcntl \
            sockets

RUN pecl install amqp && echo "extension=amqp.so" > /usr/local/etc/php/conf.d/amqp.ini \
    && rm -fr /tmp/pear

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

ARG UID
ARG GID
ARG USER
ARG GROUP

RUN addgroup --system --gid ${GID} ${GROUP} \
 && adduser --system --no-create-home -uid ${UID} --gid ${GID} \
    --disabled-password --disabled-login ${GROUP}

RUN rm /usr/local/etc/php-fpm.d/zz-docker.conf

COPY php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
