
docker compose down
docker container prune -f

if [ -e ~/Projects/braintree-laravel-scratch ]; then
    cd ~/Projects/braintree-laravel-scratch || exit;

    docker compose down
    docker container prune -f
fi

cd ~/Projects || exit;
rm -Rf ~/Projects/braintree-laravel-scratch

## Install

composer create-project laravel/laravel=11.* braintree-laravel-scratch
cd ~/Projects/braintree-laravel-scratch || exit;

## Git (pre)

git init
git add .
git commit -m "Init"

## Docker (Setup)
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/docker-compose.yml -o docker-compose.yml
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/up.sh -o up.sh
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/down.sh -o down.sh
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/bash.sh -o bash.sh

chmod +x ./*.sh

git add .
git commit -m "Docker"

#
# Composer
#
docker compose run --rm web bash -c "composer require braintree/braintree_php"

#
# Artisan
#
docker compose run --rm web bash -c "php artisan make:controller BraintreeController"

#
# GitHub Overrides
#
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/routes/web.php -o ./routes/web.php
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/config/braintree.php -o ./config/braintree.php
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/app/Http/Controllers/BraintreeController.php -o ./app/Http/Controllers/BraintreeController.php
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/resources/views/welcome.blade.php -o ./resources/views/welcome.blade.php
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/public/style.css -o ./public/style.css

#
# Env
#
curl -H 'Cache-Control: no-cache, no-store' https://raw.githubusercontent.com/rossedlin/braintree-laravel/master/.env.example -o ./.env.example
docker compose run --rm web bash -c "rm .env; cp .env.example .env; php artisan key:generate"

#
# Git (post)
#
git add .

#
# Docker (Run)
#
docker compose up -d
