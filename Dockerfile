FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Install PHP extensions and system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpq-dev \
    oniguruma-dev \
    libzip-dev \
    icu-dev \
    sqlite-dev \
    && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    zip \
    intl \
    && docker-php-ext-enable \
    pdo \
    pdo_sqlite \
    mbstring \
    zip \
    intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files
COPY composer.json ./

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction --ignore-platform-reqs

# Copy application code
COPY . .

# Create bootstrap cache dir
RUN mkdir -p /app/bootstrap/cache

# Install Node.js and build assets
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Final production image
FROM php:8.4-fpm-alpine

WORKDIR /app

# Install runtime dependencies only
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpq \
    oniguruma \
    libzip \
    icu \
    sqlite-libs

# Copy PHP application from builder
COPY --from=builder /app /app

# Copy compiled assets from frontend builder
COPY --from=frontend /app/public/build /app/public/build

# Create necessary directories
RUN mkdir -p /app/bootstrap/cache /app/storage \
    && chown -R www-data:www-data /app \
    && chmod -R 755 /app/bootstrap/cache /app/storage

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 8000

# Set environment
ENV APP_ENV=production

# Run supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
