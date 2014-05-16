#!/bin/bash

php artisan dump-autoload
php artisan migrate
php artisan migrate --bench="fintech-fab/qiwi-gate"
php artisan migrate --bench="fintech-fab/qiwi-shop"
php artisan asset:publish --bench="fintech-fab/qiwi-shop"
php artisan asset:publish --bench="fintech-fab/qiwi-gate"

php artisan migrate --env="testing"
php artisan migrate --bench="fintech-fab/qiwi-gate" --env="testing"
php artisan migrate --bench="fintech-fab/qiwi-shop" --env="testing"
