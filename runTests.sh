#/bin/bash

php artisan test
docker-compose run --rm --user="$UID:$GID" php_signing vendor/bin/phpunit tests/Feature/Query --configuration=phpunit-db.xml
docker-compose run --rm --user="$UID:$GID" php_signing vendor/bin/phpunit tests/Integration --configuration=phpunit-db.xml


