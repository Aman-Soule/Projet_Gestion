FROM php:8.2-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    opcache

# Activer les modules Apache
RUN a2enmod rewrite headers

# Copier l'application
COPY . /var/www/html/

# Configurer PHP pour l'environnement de production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/cache

WORKDIR /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]