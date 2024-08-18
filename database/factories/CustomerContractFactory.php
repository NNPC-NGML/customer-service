<?php

namespace Database\Factories;

use App\Models\User;

use App\Models\CustomerContractType;
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
            'customer_id' => User::factory()->create()->id,
            'customer_site_id' => User::factory()->create()->id,
            'contract_type_id' => CustomerContractType::factory()->create()->id,
            'created_by_user_id' => User::factory()->create()->id,
            'before_erp' => $this->faker->boolean,
            'status' => $this->faker->boolean,
        ];
    }
}
