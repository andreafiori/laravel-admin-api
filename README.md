# Laravel API Admin

A Laravel PHP APIs example application with documentation.

## Requirements

- PHP >= 7.4
- MySQL
- Composer
- Docker

## Installation

You can install the laravel dependencies with composer install but the application uses Docker anyway:

Create containers backend, frontend and db:

    docker compose up -d

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
    
Login endpoint:

    POST /api/login

If you obtain the error: Personal access client not found. Please create one.

    php artisan passport:install

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

## Notes

This an API application so the user interface parts should be in another application.
In fact, this example cannot be useful if you want to build a classic Laravel MCV application with all the views.
In this case, you must use something like Jetstream to separate the frontend and backend areas.

## TODO

- Testing
- Travis CI integration
