<?php

namespace Tests\Feature\Http\Controllers;

use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_cannot_access_without_permission()
    {
        $response = $this->get( '/api/products');
        $response->assertStatus(401);
    }

    public function test_can_add_product()
    {
        $faker = Factory::create();

        $response = $this->actingAs($this->createUserAdmin(), 'api')
                        ->json('POST', '/api/products', [
                            'image' => $image = $faker->imageUrl(),
                            'title' => $title = $faker->text(30),
                            'description' => $description = $faker->text,
                            'price' => $price = $faker->numberBetween(10, 100),
                        ]);

        $response->assertJsonStructure([
            'id', 'image', 'description', 'price'
        ])
            ->assertJson([
                'image' => $image,
                'title' => $title,
                'description' => $description,
                'price' => $price,
            ])
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('products', [
            'image' => $image,
            'title' => $title,
            'description' => $description,
            'price' => $price,
        ]);
    }

    public function test_can_return_a_collection_of_paginated_products()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                        ->json('GET', '/api/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'image', 'title', 'description']
                    ],
                    'links' => ['first', 'last', 'prev', 'next'],
                    'meta' => [
                        'current_page', 'last_page', 'from', 'to',
                        'path', 'per_page', 'total'
                    ]
                ]);
    }

    public function test_will_return_single_prodcut()
    {
        // Given
        $product = $this->create('Product');

        // When
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('GET', 'api/products/'.$product->id);

        // Then
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                   'id',
                    'image',
                    'title',
                    'description'
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'image' => $product->image,
            'title' => $product->title,
            'description' => $product->description,
        ]);
    }

    public function test_will_fail_with_a_404_if_prodcut_is_not_found()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                                ->json('GET', 'api/products/-1');

        // Debug: var_dump($response->getOriginalContent());
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_update_a_product()
    {
        $product = $this->create('Product');

        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('PUT', 'api/products/'.$product->id, [
                                'title' => $product->title.'_updated',
                                'description' => $product->description.'_description',
                                'price' => $product->price + 10,
                            ]);

        $productUpdated = [
            'id' => $product->id,
            'image' => $product->image,
            'title' => $product->title.'_updated',
            'description' => $product->description.'_description',
            'price' => $product->price + 10,
        ];

        $response->assertStatus(Response::HTTP_ACCEPTED);
                // ->assertExactJson($productUpdated);

        $this->assertDatabaseHas('products', $productUpdated);
    }

    public function test_cannot_delete_not_found_product()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('DELETE', 'api/products/-1');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_delete_a_product()
    {
        $product = $this->create('Product');

        $response = $this->actingAs($this->createUserAdmin(), 'api')
            ->json('DELETE', 'api/products/'.$product->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT)->assertSee(null);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
