FROM php:8.2-apache
 
# Instalar extensión mysqli
RUN docker-php-ext-install mysqli
 
# Copiar todos los archivos PHP al directorio de Apache
COPY . /var/www/html/
 
# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html/
 
# Exponer puerto 80
EXPOSE 80
