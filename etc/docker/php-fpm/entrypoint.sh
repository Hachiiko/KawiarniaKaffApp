#!/bin/bash

# Install backend dependencies
composer install

# Wait until MySQL container is ready
until php bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
	(>&2 echo "Waiting for MySQL to be ready...")
	sleep 1
done

php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction

# Fix the permissions
chmod -R 777 /application

/usr/sbin/php-fpm8.0
