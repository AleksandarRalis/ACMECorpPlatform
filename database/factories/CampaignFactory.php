<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\User;
use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['environment', 'education', 'health', 'community', 'animals', 'arts']),
            'goal_amount' => $this->faker->randomFloat(2, 1000, 50000),
            'current_amount' => 0,
            'image_url' => $this->faker->imageUrl(640, 480, 'campaign'),
            'status' => CampaignStatus::PENDING->value,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_by' => User::factory(),
        ];
    }

    /**
     * State for active campaigns
     */
    public function active(): self
    {
        return $this->state(fn () => [
            'status' => CampaignStatus::ACTIVE->value,
            'start_date' => now()->subDays(rand(1, 30)),
            'end_date' => now()->addDays(rand(30, 180)),
        ]);
    }

    /**
     * State for completed campaigns
     */
    public function completed(): self
    {
        return $this->state(fn () => [
            'status' => CampaignStatus::COMPLETED->value,
            'start_date' => now()->subMonths(rand(2, 6)),
            'end_date' => now()->subDays(rand(1, 30)),
            'current_amount' => $this->faker->randomFloat(2, 1000, 50000),
        ]);
    }

    /**
     * State for cancelled campaigns
     */
    public function cancelled(): self
    {
        return $this->state(fn () => [
            'status' => CampaignStatus::CANCELLED->value,
            'end_date' => now()->subDays(rand(1, 30)),
        ]);
    }

    /**
     * State for inactive campaigns
     */
    public function inactive(): self
    {
        return $this->state(fn () => [
            'status' => CampaignStatus::INACTIVE->value,
        ]);
    }

    /**
     * State for campaigns that have reached their goal
     */
    public function goalReached(): self
    {
        return $this->state(function () {
            $goalAmount = $this->faker->randomFloat(2, 1000, 10000);
            return [
                'goal_amount' => $goalAmount,
                'current_amount' => $goalAmount + $this->faker->randomFloat(2, 100, 1000),
                'status' => CampaignStatus::ACTIVE->value,
            ];
        });
    }

    /**
     * State for campaigns with progress
     */
    public function withProgress(): self
    {
        return $this->state(function () {
            $goalAmount = $this->faker->randomFloat(2, 1000, 10000);
            return [
                'goal_amount' => $goalAmount,
                'current_amount' => $this->faker->randomFloat(2, 100, $goalAmount * 0.8),
                'status' => CampaignStatus::ACTIVE->value,
            ];
        });
    }

    /**
     * State for environment campaigns
     */
    public function environment(): self
    {
        return $this->state(fn () => ['category' => 'environment']);
    }

    /**
     * State for education campaigns
     */
    public function education(): self
    {
        return $this->state(fn () => ['category' => 'education']);
    }

    /**
     * State for health campaigns
     */
    public function health(): self
    {
        return $this->state(fn () => ['category' => 'health']);
    }
} 