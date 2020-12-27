<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;

class OrdersTest extends TestCase
{
    /**
     * Show all orders
     *
     * @return void
     */
    public function testShowAllOrdersTest()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-users']
        );

        $response = $this->get('/api/orders');

        $response->assertStatus(200);
    }

    /**
     * Select single order
     *
     * @return void
     */
    public function testShowSingleOrderTest()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-users']
        );

        $response = $this->get('/api/orders/1');

        $response->assertStatus(200);
    }
}
