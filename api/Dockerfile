# Use the official PHP 8.3 image with Apache
FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy application code into the container
COPY . .

# Copy custom Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache's mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Set correct permissions for the application files (optional but recommended)
RUN chown -R www-data:www-data /var/www/html  # Change ownership to the www-data user