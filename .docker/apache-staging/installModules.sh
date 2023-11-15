#!/usr/bin/env bash

cd /var/application
rm -rf composer.lock
rm -rf wem/modules/contrib

export COMPOSER_ALLOW_SUPERUSER=1
composer install
drush cim -y
drush cr
