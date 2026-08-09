FROM php:8.5.7-apache

#extensao do php pro mysql e laravel 
RUN docker-php-ext-install pdo pdo_mysql mysqli bcmath

#atiova o modo do apache de reescrever uma url para as rotas do laravel
RUN a2enmod rewrite

#altera o diretorio padrao do apache para a pasta public do laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

#instala o composer da imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

#copia os arquivos de configuração para aproveitar o cache do docker e nao baixar tudo de novo sempre que houver alteracao no codigo
COPY composer.json composer.lock* ./

#copia o resto da aplicacao para dentro do container
COPY . .

#certifica de que os diretorios existem e que tem as devidas permissoes para o laravel funcionar corretamente
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80