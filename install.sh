#/bin/bash

cp .env.dev .env
php artisan key:generate
docker-compose run --rm --user="$UID:$GID" php_signing php /var/www/html/signingapp/artisan storage:link
sh migrate.sh



