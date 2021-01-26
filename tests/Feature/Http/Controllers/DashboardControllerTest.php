<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function test_cannot_access_without_permission()
    {
        $response = $this->get( '/api/chart');

        // Note: check return error on App\Exceptions\Handler.php
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_see_chart()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                        ->json('GET', '/api/chart');

        $response->assertStatus(200);
    }

    // TODO test cannot see order with a non admin user
}
