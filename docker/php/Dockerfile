FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev zip unzip libpq-dev supervisor \
    librabbitmq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install redis && docker-php-ext-enable redis

RUN pecl install amqp && docker-php-ext-enable amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/notifier-app

COPY ./notifier-app /var/www/notifier-app

RUN composer install --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /var/www/notifier-app \
    && chmod -R 755 /var/www/notifier-app/storage \
    && chmod -R 755 /var/www/notifier-app/bootstrap/cache

# Supervisor config for queue workers
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]