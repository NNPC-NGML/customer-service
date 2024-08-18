<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContractSignature>
 */
class CustomerContractSignatureFactory extends Factory
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
            'customer_id' => User::factory()->create()->id,
            'customer_site_id' => User::factory()->create()->id,
            'signature' => $this->faker->md5,
            'title' => $this->faker->jobTitle,
            'created_by_user_id' => User::factory()->create()->id,
            'signature_type' => $this->faker->randomElement(['user_id', 'customer_id', 'file_path']),
            'status' => $this->faker->boolean,
        ];
    }
}
