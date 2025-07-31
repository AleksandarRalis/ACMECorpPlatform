<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Administrator role with full access.',
        ];
    }

    /**
     * State for Admin role.
     */
    public function admin(): self
    {
        return $this->state(fn () => [
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Administrator role with full access.',
        ]);
    }

    /**
     * State for Employee role.
     */
    public function employee(): self
    {
        return $this->state(fn () => [
            'name' => 'employee',
            'display_name' => 'Employee',
            'description' => 'Standard employee role.',
        ]);
    }
}
