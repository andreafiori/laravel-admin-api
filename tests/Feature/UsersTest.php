<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;

class UsersTest extends TestCase
{
    /**
     * Select single user
     *
     * @return void
     */
    public function testShowAllUsersTest()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-users']
        );

        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    /**
     * Select single user
     *
     * @return void
     */
    public function testShowSingleUserTest()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-users']
        );

        $response = $this->get('/api/users/1');

        $response->assertStatus(200);
    }
}
