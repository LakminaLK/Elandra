# Minimal Laravel Docker setup for Railway
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install minimal dependencies including MongoDB
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    libssl-dev \
    pkg-config \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-req=ext-mongodb

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Apache configuration
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    # Health check endpoint\n\
    Alias /health /var/www/html/public/health.txt\n\
    Alias /healthcheck.php /var/www/html/public/healthcheck.php\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Create health files
RUN echo 'OK' > /var/www/html/public/health.txt
RUN echo '<?php echo "OK"; ?>' > /var/www/html/public/healthcheck.php

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]