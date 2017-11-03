FROM php:7.1-fpm

# Installing dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mysql-client \
    libmcrypt-dev \
    locales \
    zip

COPY php.ini /usr/local/etc/php/php.ini

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installing extensions
RUN docker-php-ext-install mcrypt pdo_mysql

# Setting locales
RUN echo fr_FR.UTF-8 UTF-8 > /etc/locale.gen && locale-gen

# Changing Workdir
WORKDIR /application

RUN mkdir -p \
		var/sessions \
	&& chown -R www-data var
