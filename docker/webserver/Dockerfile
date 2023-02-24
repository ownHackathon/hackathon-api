FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl wget git unzip netcat libxml2-dev libpq-dev \
      libzip-dev wget sendmail git && \
    pecl install apcu &&\
    pecl install xdebug &&\
    docker-php-ext-enable xdebug &&\
    a2enmod rewrite &&\
    a2enmod headers

RUN docker-php-ext-install opcache pdo_mysql zip intl
RUN docker-php-ext-enable opcache pdo_mysql zip apcu

RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN service apache2 restart
