<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function test_cannot_access_without_permission()
    {
        $response = $this->get( '/api/orders');
        $response->assertStatus(401);
    }

    // TODO test can show orders as Admin, test error 404
}
