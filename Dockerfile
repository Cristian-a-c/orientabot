FROM php:8.2-cli-bullseye AS build

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    curl \
    gnupg \
    ca-certificates \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip intl pcntl bcmath gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress

COPY package.json package-lock.json ./
RUN npm install

RUN npm run build
RUN cp .env.example .env
RUN php artisan key:generate --force
RUN php artisan route:cache && php artisan view:cache

FROM php:8.2-cli-bullseye

RUN apt-get update && apt-get install -y \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip intl pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --from=build /var/www/html /var/www/html

RUN useradd -m appuser && chown -R appuser:appuser /var/www/html
USER appuser

EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
