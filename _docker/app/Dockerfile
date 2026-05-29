FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
      apt-utils \
      default-mysql-client \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./_docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN pecl install pcov && docker-php-ext-enable pcov
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]