<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContractAddendum>
 */
class CustomerContractAddendumFactory extends Factory
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
            'parent_contract_id' => CustomerContract::factory(),
            'child_contract_id' => CustomerContract::factory(),
            'created_by_user_id' => User::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
