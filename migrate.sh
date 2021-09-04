#/bin/bash

docker-compose run --rm --user="$UID:$GID" php_signing php /var/www/html/signingapp/artisan migrate



