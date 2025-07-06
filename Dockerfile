FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl libpng-dev libonig-dev libxml2-dev git \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application Laravel
COPY . /var/www/html

# Activer mod_rewrite (nécessaire pour Laravel)
RUN a2enmod rewrite

# Configuration Apache : définir le bon DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Définir les permissions correctes
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Définir le dossier de travail
WORKDIR /var/www/html

# Installer les dépendances PHP (Laravel)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exposer le port par défaut d'Apache
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
