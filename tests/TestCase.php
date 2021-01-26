<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    use DatabaseTransactions, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // $this->artisan('migrate');
        $this->artisan('db:seed'); // OR $this->seed();
        $this->artisan('passport:install');
    }

    public function tearDown(): void
    {
        $this->artisan('migrate:reset');
    }

    public function create($model, array $attributes = [], $resource = true)
    {
        $resourceClass = "App\\Http\\Resources\\".$model."Resource";
        $modelClass = "App\\Models\\".$model;
        $modelClassInstance = new $modelClass();
        $modelClassFactory = $modelClassInstance::factory()->create($attributes);

        if (!$resource) {
            return $modelClassFactory;
        }

        return new $resourceClass($modelClassFactory);
    }

    public function createUserAdmin()
    {
        $faker = Factory::create();

        $user = User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->email,
            'password' => $faker->password,
            'role_id' => 1, // Admin
        ]);

        return $user;
    }
}
