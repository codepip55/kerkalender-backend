# Stage 1: Build Frontend Assets
FROM node:20 as frontend-builder

# Set working directory for frontend
WORKDIR /app

# Copy package.json and package-lock.json for npm dependencies
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm install

# Copy the rest of the application files (required for building assets)
COPY . .

# Build frontend assets (e.g., Tailwind CSS)
RUN npm run build

# Stage 2: Build Laravel Application
FROM php:8.2-fpm as backend-builder

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && docker-php-ext-enable pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory for Laravel
WORKDIR /var/www/html

# Copy the Laravel application source code
COPY . .

# Copy frontend assets from the frontend-builder stage
COPY --from=frontend-builder /app/public public

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Clear Laravel cache
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Set up storage symlinks
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan storage:link

# Expose the application port
EXPOSE 8000

# Start the built-in Laravel PHP server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
