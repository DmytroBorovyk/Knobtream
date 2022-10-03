# Setup instructions

- run `git clone https://github.com/DmytroBorovyk/Knobtream.git`

open IDE Project from existing files in this directory
- `cp .env.example .env`
- set DB_PASSWORD in .env
- `docker compose up -d`
  
Wait until build.

- Inside container (see below)
  - run `php artisan optimize:clear`
  - make sure that there is no key
  - run `php artisan key:generate`
  - run `php artisan migrate`
  - run `php artisan optimize`
  
## to open php container
run `docker exec -it knobtream-local-api bash`

## to run php-cs-fixer
run inside php container `vendor/bin/php-cs-fixer fix --config=php_cs.dist.php`

## to generate API documentation
run inside php container `php artisan l5-swagger:generate`

to check documentation go forward by uri `http://localhost:2715/api/documentation`
