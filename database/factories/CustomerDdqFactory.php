<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerDdq>
 */
class CustomerDdqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'data' => $this->faker->sentence(),
            'customer_id' => $this->faker->numberBetween(1, 10),
            'customer_site_id' => $this->faker->numberBetween(1, 10),
            'group_id' => $this->faker->numberBetween(1, 10),
            'subgroup_id' => $this->faker->numberBetween(1, 10),
            'document_type' => $this->faker->randomElement(['string', 'file']),
            'created_by_user_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
