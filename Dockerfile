# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Activer les extensions PHP nécessaires (exemple : mysqli et pdo_mysql pour MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier ton projet dans le répertoire attendu par Apache
COPY . /var/www/html/

# Donner les bons droits à Apache pour lire les fichiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activer le module Apache rewrite (utile pour les frameworks type Laravel, Symfony)
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Exposer le port 80
EXPOSE 80

# Lancer Apache en mode foreground
CMD ["apache2-foreground"]
