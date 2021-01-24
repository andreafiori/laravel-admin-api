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
        $user = $this->createTestUser();

        $response = $this->actingAs($user, 'api')
                        ->json('GET', '/api/chart');

        $response->assertStatus(200);
    }
}
