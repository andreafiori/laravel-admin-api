<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;

class ProductsTest extends TestCase
{
    /**
     * Show all products
     *
     * @return void
     */
    public function testShowAllProductsTest()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-users']
        );

        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }

    /**
     * Select single product
     *
     * @return void
     */
    public function testShowSingleProductsTest()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-users']
        );

        $response = $this->get('/api/products/1');

        $response->assertStatus(200);
    }
}
