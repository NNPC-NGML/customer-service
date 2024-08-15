<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CustomerContractTemplate;
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
        $contract = CustomerContractTemplate::factory();
        return [
            'customer_id' => User::factory()->create()->id,
            'customer_site_id' => $this->faker->numberBetween(1, 100),
            'contract_id' => CustomerContractTemplate::factory()->create()->id,
            'title_id' => $this->faker->unique()->word,
            'created_by_user_id' => User::factory()->create()->id,
            'status' => $this->faker->boolean(),
        ];
    }
}
