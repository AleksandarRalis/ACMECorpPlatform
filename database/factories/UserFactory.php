<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'employee_id' => 'EMP' . $this->faker->unique()->numerify('###'),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'department' => $this->faker->randomElement(['Marketing', 'Sales', 'IT']),
            'position' => $this->faker->jobTitle(),
            'is_active' => UserStatus::ACTIVE->id(),
            'phone' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'),
            'role_id' => UserRole::ADMIN->id(),
        ];
    }

    /**
     * Define an inactive user state
     */
    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => UserStatus::INACTIVE->id()]);
    }

    public function active(): self
    {
        return $this->state(fn () => ['is_active' => UserStatus::ACTIVE->id()]);
    }

    public function admin(): self
    {
        return $this->state(fn () => ['role_id' => UserRole::ADMIN->id()]);
    }

    public function employee(): self
    {
        return $this->state(fn () => ['role_id' => UserRole::EMPLOYEE->id()]);
    }
}
