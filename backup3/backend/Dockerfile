FROM php:8.2-apache

# Bật các module cần thiết
RUN a2enmod rewrite

# Cài extension MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy toàn bộ source code backend vào container
COPY . /var/www/html/

# Cấp quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# Cho phép .htaccess hoạt động
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT /var/www/html

EXPOSE 80