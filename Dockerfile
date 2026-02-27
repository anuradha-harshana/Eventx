FROM php:8.2-apache

# ── System dependencies ─────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        zip \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# ── PHP Extensions ───────────────────────────────────────────────────
RUN docker-php-ext-install mysqli pdo pdo_mysql

# ── Enable Apache mod_rewrite ────────────────────────────────────────
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf


# ── Disable OPcache for development (important for live updates) ────
RUN echo "opcache.enable=0" >> /usr/local/etc/php/conf.d/opcache.ini
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# ── Set working directory ────────────────────────────────────────────
WORKDIR /var/www/html

EXPOSE 80
