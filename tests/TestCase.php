<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    use DatabaseTransactions, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();

        // Install passport
        $this->artisan('passport:install');
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

    public function createTestUser()
    {
        Role::create([
            'name' => 'Admin'
        ]);

        $modelClassFactory = User::factory()->create();

        return $modelClassFactory;
    }
}
