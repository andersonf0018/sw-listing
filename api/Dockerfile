FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    supervisor \
    cron \
    zip \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "*/5 * * * * cd /var/www && /usr/local/bin/php artisan schedule:run >> /var/log/cron-schedule.log 2>&1" > /etc/cron.d/laravel-scheduler
RUN chmod 0644 /etc/cron.d/laravel-scheduler
RUN crontab /etc/cron.d/laravel-scheduler

COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

COPY composer.json composer.lock ./
COPY .env.example .env

RUN composer install --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload
RUN php artisan key:generate
RUN php artisan optimize

EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]