FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory for the application
WORKDIR /var/www/html

# Copy application code into the container
COPY . .

# Set correct permissions (optional but recommended)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
