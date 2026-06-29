FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql


# 1. Update ports.conf to listen on 8080
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# 2. Update the default site to listen on 8080 AND ensure DocumentRoot is correct
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8080>/g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html|g' /etc/apache2/sites-available/000-default.conf






# --------------------------------------

# Copy your backend API
COPY ./backend /var/www/html/api

# Copy your frontend files
COPY ./frontend /var/www/html

# Copy your cloud-storage folder
COPY ./cloud-storage /var/www/html/cloud-storage

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
