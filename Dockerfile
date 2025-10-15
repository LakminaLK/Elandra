# Multi-stage build for Laravel
# Stage 1: Build frontend assets
FROM node:18-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package.json package-lock.json* ./

# Install Node.js dependencies (including dev dependencies for build)
RUN npm ci

# Copy frontend source code
COPY resources/ ./resources/
COPY vite.config.js tailwind.config.js postcss.config.js ./

# Build frontend assets
RUN npm run build

# Stage 2: PHP Application
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

# Copy built frontend assets from frontend-builder stage
COPY --from=frontend-builder /app/public/build ./public/build

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

# Create startup script with better error handling and logging
RUN echo '#!/bin/bash\n\
set -e\n\
echo "Starting Elandra deployment..."\n\
\n\
# Show environment info\n\
echo "Environment: $APP_ENV"\n\
echo "Database Host: $DB_HOST"\n\
echo "Database Name: $DB_DATABASE"\n\
\n\
# Clear caches first\n\
echo "Clearing Laravel caches..."\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan view:clear\n\
\n\
# Wait for database with better checking\n\
echo "Waiting for MySQL database connection..."\n\
max_attempts=30\n\
attempt=1\n\
\n\
while [ $attempt -le $max_attempts ]; do\n\
    if php artisan db:show --database=mysql > /dev/null 2>&1; then\n\
        echo "✅ MySQL database connected successfully"\n\
        break\n\
    else\n\
        echo "⏳ Attempt $attempt/$max_attempts: Database not ready, waiting 5 seconds..."\n\
        sleep 5\n\
        attempt=$((attempt + 1))\n\
    fi\n\
done\n\
\n\
if [ $attempt -gt $max_attempts ]; then\n\
    echo "❌ Failed to connect to database after $max_attempts attempts"\n\
    echo "Database connection details:"\n\
    php artisan config:show database\n\
    exit 1\n\
fi\n\
\n\
# Run database migrations with verbose output\n\
echo "Running database migrations..."\n\
php artisan migrate --force --verbose\n\
\n\
# Show migration status\n\
echo "Checking migration status:"\n\
php artisan migrate:status\n\
\n\
# Create storage link\n\
echo "Creating storage link..."\n\
php artisan storage:link --force || true\n\
\n\
# Cache configuration for production\n\
echo "Caching Laravel configuration..."\n\
php artisan config:cache\n\
\n\
# Show final database tables\n\
echo "Final database tables:"\n\
php artisan db:table --database=mysql || true\n\
\n\
# Start Apache\n\
echo "🚀 Starting Apache web server..."\n\
exec apache2-foreground\n\
' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 80

# Start with migration script
CMD ["/usr/local/bin/start.sh"]