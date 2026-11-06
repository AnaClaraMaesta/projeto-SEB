FROM php:8.5.7-apache

#extensao do php pro laravel 
RUN docker-php-ext-install pdo bcmath

#atiova o modo do apache de reescrever uma url para as rotas do laravel
RUN a2enmod rewrite

#altera o diretorio padrao do apache para a pasta public do laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_pgsql pgsql zip
    
#instala o composer da imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

#copia os arquivos de configuração para aproveitar o cache do docker e nao baixar tudo de novo sempre que houver alteracao no codigo
COPY composer.json composer.lock* ./

#instala as dependencias dentro do container: a pasta vendor/ e ignorada pelo .dockerignore
#e antes vinha do bind mount da maquina host
RUN composer install --no-interaction --no-scripts --prefer-dist --no-progress

#copia o resto da aplicacao para dentro do container
COPY . .

#regenera o autoloader otimizado agora que app/ existe, e ajusta as permissoes do laravel
RUN composer dump-autoload --no-interaction --no-scripts --optimize \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
