<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractSection;
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
            'contract_id' => CustomerContract::factory()->create()->id,
            'section_id' => CustomerContractSection::factory(),
            'details' => $this->faker->paragraph,
            'customer_id' => User::factory()->create()->id,
            'customer_site_id' => User::factory()->create()->id,
            'created_by_user_id' => User::factory()->create()->id,
            'status' => $this->faker->boolean,
        ];
    }
}
