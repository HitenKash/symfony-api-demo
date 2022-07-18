FROM php:7.4-fpm

RUN apt-get update && apt-get -y --no-install-recommends install \
    ca-certificates \
    git\
    curl\
    gnupg\
    dirmngr\
    libpng-dev\
    libzip-dev\
    libxslt-dev\
    libicu-dev\
    libonig-dev\
    joe\
    wget\
    unzip\
    sudo

RUN apt-get install -y \
    libpng-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libxpm-dev \
    libwebp-dev\
    libxext6\
    libfontconfig1\
    libxrender1

RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    --with-xpm=/usr/include \
    --with-webp=/usr/include/

RUN NUMPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NUMPROC} pdo_mysql gd exif xsl soap mbstring zip xml intl pcntl

RUN cp /usr/local/bin/php /usr/bin/php

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

#ADD php.ini /usr/local/etc/php/php.ini

RUN ln -snf /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

ADD . /var/www/html
WORKDIR /var/www/html
#RUN chmod +x docker-install.sh && sh docker-install.sh
CMD ["/bin/bash", "-c", "chmod +x ./docker-install.sh && ./docker-install.sh"]
