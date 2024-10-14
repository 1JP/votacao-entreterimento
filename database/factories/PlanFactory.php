<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'number_film' => fake()->randomDigit(),
            'number_book' => fake()->randomDigit(),
            'number_serie' => fake()->randomDigit(),
            'value' => fake()->randomFloat(),
            'customer_id' => fake()->text(100),
            'active' => fake()->boolean(),
        ];
    }
}
