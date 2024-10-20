<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['ACTIVE', 'EXPIRED', 'CANCELED', 'SUSPENDED', 'OVERDUE', 'TRIAL', 'PENDING', 'PENDING_ACTION']),
            'customer_id' => fake()->text(100),
        ];
    }
}
