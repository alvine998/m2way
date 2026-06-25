FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    oniguruma-dev \
    libzip-dev \
    libxml2-dev \
    curl-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    xml \
    zip \
    gd

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]

FROM base AS dev

COPY --from=node:22-alpine /usr/lib/node_modules /usr/lib/node_modules
COPY --from=node:22-alpine /usr/local/bin/node /usr/local/bin/node
RUN ln -sf /usr/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

FROM base AS production

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

COPY . .

RUN php artisan storage:link

FROM node:22-alpine AS build

WORKDIR /build
COPY package.json package-lock.json vite.config.js ./
RUN npm ci
COPY resources/ resources/
RUN npm run build

FROM production AS final
COPY --from=build /build/public/build ./public/build
