#!/bin/bash

composer install
php bin/console assets:install --symlink --relative
php bin/console doctrine:schema:update --force
php bin/console lexik:jwt:generate-keypair --skip-if-exists
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:update --force --env=test
exec php-fpm
