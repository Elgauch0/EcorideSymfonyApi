# Utilise l'image PHP avec Apache
FROM php:8.2-apache

# Variables d'env utiles en dev
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_INI_DIR=/usr/local/etc/php

# Dépendances système nécessaires (intl, zip, SSL pour MongoDB, etc.)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl \
    libicu-dev \
    libzip-dev \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP (PDO MySQL pour MariaDB, intl, zip, opcache)
RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
    intl zip opcache pdo pdo_mysql

# Extension MongoDB (pour doctrine/mongodb-odm-bundle)
RUN pecl install mongodb && rm -rf /tmp/pear \
    && docker-php-ext-enable mongodb

# Config PHP adaptée au dev (Symfony)
RUN { \
    echo 'date.timezone=Europe/Paris'; \
    echo 'memory_limit=512M'; \
    echo 'upload_max_filesize=20M'; \
    echo 'post_max_size=25M'; \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.memory_consumption=192'; \
    echo 'opcache.interned_strings_buffer=16'; \
    } > ${PHP_INI_DIR}/conf.d/dev.ini

# Apache: rewrite + headers (utile pour Symfony et CORS)
RUN a2enmod rewrite headers

# VHost (ton fichier doit pointer vers /var/www/html/public si ton projet a un dossier public)
COPY ./php/vhosts/vhosts.conf /etc/apache2/sites-available/000-default.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Répertoire de travail
WORKDIR /var/www/html
COPY ./app/composer.json ./app/composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist  --no-dev --optimize-autoloader --classmap-authoritative
COPY ./app /var/www/html/
RUN composer dump-autoload --optimize

# Droits (www-data) sur le projet; var/ est souvent bind-mounté en dev, mais on prépare quand même
RUN mkdir -p var \
    && chown -R www-data:www-data /var/www/html

# Expose le port 80
EXPOSE 80

# Démarrage Apache
CMD ["apache2-foreground"]
