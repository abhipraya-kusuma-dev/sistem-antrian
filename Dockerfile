# ============================
# Stage 1: Node for frontend
# ============================
FROM node:18-alpine as frontend

# Set working directory
WORKDIR /app

# Copy only package.json to leverage caching
COPY package*.json ./

# Install frontend dependencies
RUN npm install

# Copy the rest of the code
COPY . .

# Build Tailwind / JS assets
RUN npm run build


# ============================
# Stage 2: PHP backend
# ============================
FROM php:8.2-fpm-alpine as backend

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl-dev \
    ffmpeg \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    icu-dev \
    zlib-dev \
    libxml2-dev \
    git \
    unzip \
    openssl-dev \
    supervisor \
    shadow \
    tzdata \
    make \
    g++ \
    autoconf

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
    curl \
    fileinfo \
    gd \
    intl \
    mbstring \
    pdo_pgsql \
    pgsql \
    zip \
    opcache \
    pcntl

RUN echo "disable_functions =" > /usr/local/etc/php/conf.d/00-clear-disabled.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel code
COPY . .

# Copy built frontend assets from previous stage
COPY --from=frontend /app/public ./public
COPY --from=frontend /app/resources ./resources
COPY --from=frontend /app/node_modules ./node_modules

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan storage:link && \
    php artisan octane:install --server=swoole

# Install Laravel Octane Swoole extension
RUN pecl install swoole && \
    docker-php-ext-enable swoole

# Permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]


# ============================
# Stage 3: Node server
# ============================
FROM node:18-alpine as socket

WORKDIR /app

COPY ./server.cjs ./
COPY ./package*.json ./

RUN npm install --omit=dev

EXPOSE 3000
CMD ["node", "server.cjs"]
