# Laravel API Admin

A Laravel PHP APIs example application with tests and documentation.

## Requirements

- PHP >= 7.4
- MySQL
- Composer
- Docker

## Installation

Install dependencies:

    composer install

Create containers backend, frontend and db:

    docker-compose up -d

Run docker:

    docker compose up

Show the containers list:

    docker ps

Access the API container.

    docker exec -it admin_api sh

Migration and inserting demo data (seed):

After editing the .env file with the database connection parameter, you will be ready to migrate:

    php artisan migrate
    php artisan db:seed

PHPMyAdmin configuration is included in the docker-compose.yml so you can use it to manage MySQL databases:

    http://localhost:8081

## Laravel

    php artisan serve

## Open API

Generate Open API documentation with l5-swagger:

    php artisan l5-swagger:generate

A login endpoint with parameters (email and password) will let you to obtain a Bearer token:

    POST /api/login

If you obtain the error "Personal access client not found. Please create one.", run:

    php artisan passport:install

## Tests

Tests use Sqlite and memory database configuration (see phpunit.xml).

Run tests:

    php artisan test

## Technologies

- [Laravel 8.x](https://laravel.com/)
    - [Laravel IDE helper](https://github.com/barryvdh/laravel-ide-helper)
    - [Laravel passport](https://laravel.com/docs/8.x/passport)
- [Docker](https://www.docker.com/)
- [L6 Swagger](https://github.com/DarkaOnLine/L5-Swagger)
