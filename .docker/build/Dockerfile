FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    git \
    libsodium-dev \
    libicu-dev \
    librabbitmq-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    supervisor

RUN docker-php-ext-install pdo pdo_mysql sodium intl curl fileinfo gd zip

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY .docker/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN git clone https://github.com/MasterKafey/E5-Workshop-API.git /var/www/html

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

RUN composer install --optimize-autoloader

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]