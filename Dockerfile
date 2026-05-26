FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends\
	libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_REPO_PACKAGIST=https://mirror.abrha.net/repository/composer/


RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/shop

COPY --chown=www:www composer.json composer.lock ./
COPY --chown=www:www package.json package-lock.json ./

RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && npm install --production

COPY --chown=www:www . .

RUN chown -R www-data:www-data /var/www/shop/storage /var/www/shop/bootstrap/cache

RUN echo "opcache.enable=1\nopcache.memory_consumption=128\nopcache.max_accelerated_files=20000" > /usr/local/etc/php/conf.d/opcache-recommended.ini

EXPOSE 9000
CMD ["php-fpm"]


