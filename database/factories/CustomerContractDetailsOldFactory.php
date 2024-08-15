<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContractDetailsOld>
 */
class CustomerContractDetailsOldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => $this->faker->numberBetween(1, 100),
            'file_path' => $this->faker->filePath(),
            'customer_id' => $this->faker->numberBetween(1, 100),
            'customer_site_id' => $this->faker->numberBetween(1, 100),
            'created_by_user_id' => User::factory()->create()->id,
            'status' => $this->faker->boolean,
        ];
    }
}
