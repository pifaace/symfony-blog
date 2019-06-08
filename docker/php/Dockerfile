FROM php:7.2-fpm

# Installing dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mysql-client \
    openssl \
    locales \
    zip \
    zlib1g-dev \
    libicu-dev \
    libzip-dev \
    chromium

RUN curl -sS -o /tmp/icu.tgz -L http://download.icu-project.org/files/icu4c/62.1/icu4c-62_1-src.tgz \
  && tar -zxf /tmp/icu.tgz -C /tmp \
  && cd /tmp/icu/source \
  && ./configure --prefix=/usr/local \
  && make \
  && make install

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installing extensions
RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    intl

RUN docker-php-ext-configure intl --with-icu-dir=/usr/local

COPY php.ini /usr/local/etc/php/php.ini

# Setting locales
RUN echo fr_FR.UTF-8 UTF-8 > /etc/locale.gen && locale-gen

# Installing composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Changing Workdir
WORKDIR /application

ENV PANTHER_NO_SANDBOX 1

RUN mkdir -p \
		var/sessions \
	&& chown -R www-data var
