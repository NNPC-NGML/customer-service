<?php

namespace Database\Factories;

use App\Models\User;

use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContractSection>
 */
class CustomerContractSectionFactory extends Factory
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
            'contract_id' => CustomerContract::factory()->create()->id,
            'title_id' => $this->faker->word,
            'created_by_user_id' => User::factory()->create()->id,
            'status' => $this->faker->boolean,
        ];
    }
}
