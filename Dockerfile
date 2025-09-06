# ====== STAGE 1 : Build & install deps ======
FROM php:8.2-apache AS build

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_INI_DIR=/usr/local/etc/php

# Dépendances système + extensions PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl \
    libicu-dev libzip-dev libssl-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl zip opcache pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/* /tmp/*

# Config PHP (prod)
RUN { \
    echo 'date.timezone=Europe/Paris'; \
    echo 'memory_limit=512M'; \
    echo 'upload_max_filesize=20M'; \
    echo 'post_max_size=25M'; \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.memory_consumption=192'; \
    echo 'opcache.interned_strings_buffer=16'; \
    } > ${PHP_INI_DIR}/conf.d/prod.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1️⃣ Copier uniquement composer.json/lock pour profiter du cache
COPY ./app/composer.json ./app/composer.lock ./

# 2️⃣ Copier bin/ et config/ pour que bin/console existe pendant install
COPY ./app/bin ./bin
COPY ./app/config ./config

# 3️⃣ Installer dépendances PHP sans scripts (pour éviter l'erreur)
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --no-cache --no-scripts

# 4️⃣ Copier le reste du code
COPY ./app /var/www/html/

# 5️⃣ Lancer les scripts Composer maintenant que tout est là
RUN composer run-script post-install-cmd || true

# 6️⃣ Optimiser autoload
RUN composer dump-autoload --optimize

# Droits
RUN mkdir -p var && chown -R www-data:www-data /var/www/html

# ====== STAGE 2 : Image finale ======
FROM php:8.2-apache

# Copier config Apache
COPY ./php/vhosts/vhosts.conf /etc/apache2/sites-available/000-default.conf

# Copier PHP + extensions depuis le build
COPY --from=build /usr/local/etc/php /usr/local/etc/php
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/bin/docker-php-* /usr/local/bin/
COPY --from=build /usr/local/sbin/docker-php-* /usr/local/sbin/

# Copier uniquement le code et vendor
WORKDIR /var/www/html
COPY --from=build /var/www/html /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
