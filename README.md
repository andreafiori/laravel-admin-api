# Laravel API Admin

A Laravel PHP APIs example application with documentation.

## Requirements

- PHP >= 7.4
- MySQL
- Composer
- Docker

## Installation

    composer install
    
## Docker

    docker compose build
    docker compose up

Access the admin API container:

    docker exec -it laravel-admin_backend_1 sh

## Laravel

    php artisan serve

Generate Open API \ Swagger documentation:

    php artisan l5-swagger:generate
    
## Run tests

    php artisan test

## Technologies

- [Laravel 8.x](https://laravel.com/)
    - [Laravel IDE helper](https://github.com/barryvdh/laravel-ide-helper)
    - [Laravel passport](https://laravel.com/docs/8.x/passport)
- [Docker](https://www.docker.com/)
- [L6 Swagger](https://github.com/DarkaOnLine/L5-Swagger)
- [React](https://en.reactjs.org/)

## Tools

- PHPStorm IDE
- Docker desktop
- Postman

## TODO

- [Code Testing](https://laravel.com/docs/8.x/http-tests)
- [Testing Laravel Passport](https://laravel.com/docs/8.x/passport#testing)
- Open API comments testing?
