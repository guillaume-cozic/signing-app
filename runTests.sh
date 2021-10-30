#/bin/bash

php artisan test
docker-compose run --rm --user="$UID:$GID" php_signing signingapp/vendor/bin/phpunit signingapp/tests/Feature/Query --configuration=signingapp/phpunit-db.xml
docker-compose run --rm --user="$UID:$GID" php_signing signingapp/vendor/bin/phpunit signingapp/tests/Repositories --configuration=signingapp/phpunit-db.xml


