<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function run()
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Editor']);
        Role::create(['name' => 'Viewer']);

        return [];
    }
}
