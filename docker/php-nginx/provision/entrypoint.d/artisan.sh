#!/bin/bash
chown -R application:application .
composer update
#/usr/local/bin/php /app/artisan optimize
#/usr/local/bin/php /app/artisan migrate --force
#/usr/local/bin/php /app/artisan key:generate --force
#/usr/local/bin/php /app/artisan optimize
