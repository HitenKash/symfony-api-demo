#!/bin/bash

composer install
php bin/console assets:install --symlink --relative
php bin/console doctrine:schema:update --force
exec php-fpm
