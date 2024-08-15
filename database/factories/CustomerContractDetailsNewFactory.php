<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CustomerContractSection;
use App\Models\CustomerContractTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContractDetailsNew>
 */
class CustomerContractDetailsNewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => $this->faker->randomDigitNotNull(),
            'section_id' =>
            CustomerContractSection::factory()->create()->id,
            'details' => $this->faker->paragraph(),
            'customer_id' =>
            User::factory()->create()->id,
            'customer_site_id' => $this->faker->randomDigitNotNull(),
            'created_by_user_id'
            => User::factory()->create()->id,
            'status' => $this->faker->boolean(),
        ];
    }
}
