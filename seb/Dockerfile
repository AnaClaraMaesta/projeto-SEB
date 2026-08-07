FROM php:8.5.7-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli bcmath
RUN a2enmod rewrite
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock* ./
COPY . .
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache
EXPOSE 80