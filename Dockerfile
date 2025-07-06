FROM php:8.2-apache

# Installer les dépendances PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl libpng-dev libonig-dev libxml2-dev git \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier l'application Laravel dans le conteneur
COPY . /var/www/html

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Définir le dossier de travail
WORKDIR /var/www/html

# Installer les dépendances Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage

EXPOSE 80

CMD ["apache2-foreground"]
