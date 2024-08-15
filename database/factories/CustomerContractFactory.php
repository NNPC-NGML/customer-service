<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContract>
 */
class CustomerContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' =>
            User::factory()->create()->id,
            'customer_site_id' => $this->faker->numberBetween(1, 1000),
            'contract_type_id' => $this->faker->numberBetween(1, 10),
            'created_by_user_id' =>
            User::factory()->create()->id,
            'before_erp' => $this->faker->boolean,
            'status' => $this->faker->boolean,
        ];
    }
}
