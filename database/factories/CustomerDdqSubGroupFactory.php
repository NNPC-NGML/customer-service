<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerDdqSubGroup>
 */
class CustomerDdqSubGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'customer_ddq_group_id' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->boolean(),
        ];
    }
}
