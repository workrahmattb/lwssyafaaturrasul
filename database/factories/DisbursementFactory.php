<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Disbursement>
 */
class DisbursementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'disbursed_at' => fake()->date(),
            'amount' => fake()->numberBetween(10000, 1000000),
            'type' => fake()->randomElement(array_keys(\App\Models\Disbursement::CATEGORIES)),
        ];
    }
}
