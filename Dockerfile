FROM php:8.2-apache

# Activer les extensions PHP nécessaires (aucune base de données ici, donc minimal)
RUN docker-php-ext-install mysqli

# Copier le projet dans le conteneur
COPY . /var/www/html/

# Modifier le DocumentRoot pour pointer vers le dossier public
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Droits d'accès
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activer le module rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
