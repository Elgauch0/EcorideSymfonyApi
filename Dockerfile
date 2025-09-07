# ====== STAGE 1 : Build Dependencies and Application ======
FROM php:8.2-apache AS build

# Set environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_INI_DIR=/usr/local/etc/php

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl \
    libicu-dev libzip-dev libssl-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl zip opcache pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/* /tmp/*


# Configure PHP for production
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

# Copy Composer from an official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files (optimizes Docker layer caching)
COPY ./app /var/www/html/

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --no-cache --no-scripts

# Run Composer scripts (after all files are copied)
RUN composer run-script post-install-cmd || true

# Optimize Composer autoload files
RUN composer dump-autoload --optimize

# Set file ownership for Apache user
RUN chown -R www-data:www-data /var/www/html


# ====== STAGE 2 : Final Production Image ======
FROM php:8.2-apache



# Install necessary runtime dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libzip-dev \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername


# Copy Apache config from host
COPY ./php/vhosts/prod.conf /etc/apache2/sites-available/000-default.conf

# Copy PHP configs and extensions from the build stage
COPY --from=build /usr/local/etc/php /usr/local/etc/php
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/bin/docker-php-* /usr/local/bin/
COPY --from=build /usr/local/sbin/docker-php-* /usr/local/sbin/

# Copy only the built code and vendor files from the build stage
WORKDIR /var/www/html
COPY --from=build /var/www/html /var/www/html

EXPOSE 80


