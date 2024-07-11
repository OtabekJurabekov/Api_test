# Use the official PHP image as a base
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Set permissions for directories and files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 *

# Switch to the www-data user
USER www-data

# Clear Composer cache
RUN composer clear-cache
RUN composer update
# Install dependencies using Composer
RUN composer install --no-interaction --no-plugins --no-scripts

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
