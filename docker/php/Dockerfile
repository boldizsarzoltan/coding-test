# Use the official PHP image as the base image
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/symfony

COPY ../../../ .

# Install project dependencies
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-progress

# Generate the autoload files
RUN composer dump-autoload --optimize

# Expose the port PHP-FPM will run on
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
