FROM php:8.2-apache
# Install additional PHP extensions
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN service apache2 restart