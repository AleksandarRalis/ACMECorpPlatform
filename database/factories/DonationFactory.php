<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'donor_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'message' => $this->faker->optional(0.7)->paragraph(),
        ];
    }

    /**
     * State for donations with messages
     */
    public function withMessage(): self
    {
        return $this->state(fn () => [
            'message' => $this->faker->paragraph(),
        ]);
    }

    /**
     * State for large donations
     */
    public function large(): self
    {
        return $this->state(fn () => [
            'amount' => $this->faker->randomFloat(2, 1000, 10000),
        ]);
    }

    /**
     * State for small donations
     */
    public function small(): self
    {
        return $this->state(fn () => [
            'amount' => $this->faker->randomFloat(2, 1, 50),
        ]);
    }

    /**
     * State for medium donations
     */
    public function medium(): self
    {
        return $this->state(fn () => [
            'amount' => $this->faker->randomFloat(2, 50, 500),
        ]);
    }

    /**
     * State for donations without messages
     */
    public function withoutMessage(): self
    {
        return $this->state(fn () => [
            'message' => null,
        ]);
    }
} 