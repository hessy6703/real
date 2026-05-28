FROM php:8.0-apache

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod ssl

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    json \
    curl \
    mbstring \
    openssl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Copy Apache config
COPY apache-config.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80 443

CMD ["apache2-foreground"]
