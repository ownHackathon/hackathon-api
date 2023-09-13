ARG PHP_VERSION=${PHP_VERSION:-8.2}
FROM php:${PHP_VERSION}-fpm-alpine

RUN apk -U upgrade
RUN apk add --no-cache rsync nodejs npm gcc freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev $PHPIZE_DEPS openssl-dev linux-headers

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install opcache gd pdo_mysql zip
RUN docker-php-ext-enable opcache gd zip pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN npm install --global yarn

RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

ARG ENV=${ENV:-production}

RUN if [ "$ENV" = "dev" ] ; then pecl install xdebug && docker-php-ext-enable xdebug; fi
RUN if [ "$ENV" = "dev" ] ; then cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini; fi
RUN if [ "$ENV" = "production" ] ; then cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini; fi

CMD ["php-fpm"]
