FROM php:8.2-apache

# Installer les dépendances système nécessaires (pdo_mysql, zip...)
RUN apt-get update && apt-get install -y libzip-dev zip unzip curl \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copier les fichiers composer pour cache Docker
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Copier tout le reste du code
COPY . .

# Modifier DocumentRoot vers public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Permissions correctes pour storage et cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
