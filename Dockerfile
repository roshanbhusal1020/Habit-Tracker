FROM node:18 as node-builder
WORKDIR /app

COPY package*.json vite.config.* ./
RUN npm install

COPY . .
RUN npm run build



FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \ 
    && docker-php-ext-install pdo pdo_mysql zip

#Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Install PHP dependencies
RUN composer install

COPY --from=node-builder /app/public/build ./public/build

# Expose port 8000 
EXPOSE 8000

# Start the PHP built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]