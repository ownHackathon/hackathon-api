FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y \
    libzip.dev \
    wget \
    sendmail \
    nodejs \
    npm \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && a2enmod rewrite \
    && a2enmod headers

RUN docker-php-ext-install opcache pdo_mysql zip
RUN docker-php-ext-enable opcache pdo_mysql zip

RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

RUN wget https://getcomposer.org/download/latest-stable/composer.phar
RUN chmod a+x composer.phar
RUN mv composer.phar /usr/local/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN service apache2 restart
