FROM php:7.2.0-fpm

# Installing dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mysql-client \
    openssl \
    locales \
    zip \
    zlib1g-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installing extensions
RUN docker-php-ext-install \
    pdo_mysql \
    zip

COPY php.ini /usr/local/etc/php/php.ini

# Setting locales
RUN echo fr_FR.UTF-8 UTF-8 > /etc/locale.gen && locale-gen

# Installing composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Changing Workdir
WORKDIR /application

RUN mkdir -p \
		var/sessions \
	&& chown -R www-data var
