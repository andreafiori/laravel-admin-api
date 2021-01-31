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

    public function test_will_fail_with_a_404_if_order_is_not_found()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
            ->json('GET', 'api/orders/-1');

        $response->assertStatus(404);
    }
}
