<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    public function test_cannot_access_without_permission()
    {
        $response = $this->get( '/api/roles');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_can_return_a_collection_of_paginated_roles()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
            ->json('GET', '/api/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name']
                ],
            ]);
    }

    public function test_will_fail_with_a_404_if_role_is_not_found()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('GET', 'api/roles/-1');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_will_create_a_role()
    {
        $role = $this->create('Role');

        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('GET', 'api/roles/'.$role->id);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                    ],
                ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $role->name,
        ]);
    }

    public function test_can_update_a_role()
    {
        $role = $this->create('Role');

        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('PUT', 'api/roles/'.$role->id, [
                                'name' => $role->name.'_updated',
                            ]);

        $roleUpdated = [
            'id' => $role->id,
            'name' => $role->name.'_updated',
        ];

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas('roles', $roleUpdated);
    }

    public function test_cannot_delete_not_found_role()
    {
        $response = $this->actingAs($this->createUserAdmin(), 'api')
                        ->json('DELETE', 'api/roles/-1');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_delete_a_role()
    {
        $role = $this->create('Role');

        $response = $this->actingAs($this->createUserAdmin(), 'api')
                            ->json('DELETE', 'api/roles/'.$role->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT)->assertSee(null);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
