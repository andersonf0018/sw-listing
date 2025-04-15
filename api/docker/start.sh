#!/bin/bash

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

echo "Waiting for database connection..."
while ! php -r "try { new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'Connected successfully'; } catch (PDOException \$e) { exit(1); }" > /dev/null 2>&1; do
    echo "Waiting for database connection..."
    sleep 2
done

php artisan migrate --force

php artisan queue:table
php artisan migrate --force

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf &

echo "Generating initial API statistics..."
php artisan tinker --execute="dispatch(new App\Jobs\GenerateApiStatistics());"

php artisan serve --host=0.0.0.0 --port=9000 