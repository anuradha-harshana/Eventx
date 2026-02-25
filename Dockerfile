FROM php:8.2-apache

# ── System dependencies ────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        zip \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# ── PHP extensions ─────────────────────────────────────────────────────────────
RUN docker-php-ext-install mysqli pdo_mysql

# ── Apache: enable mod_rewrite ─────────────────────────────────────────────────
RUN a2enmod rewrite

# ── Custom VirtualHost (mirrors /Eventx path so .htaccess needs no changes) ───
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# ── Application code ───────────────────────────────────────────────────────────
WORKDIR /var/www/html
COPY . .

# ── Permissions for upload directories ────────────────────────────────────────
RUN mkdir -p uploads/events uploads/products uploads/profile \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html/uploads -type d -exec chmod 755 {} \;

EXPOSE 80
