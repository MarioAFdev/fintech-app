FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar el DocumentRoot de Apache
RUN sed -ri -e 's!/var/www/html!/var/www/html/backend!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/conf-available/*.conf && \
    sed -ri -e 's!/var/www/!/var/www/html/backend!g' \
    /etc/apache2/apache2.conf