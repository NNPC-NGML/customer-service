<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerApprovalType>
 */
class CustomerApprovalTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'approval_title' => $this->faker->words(3, true),
            'decline_action' => $this->faker->url,
            'created_by_user_id' => User::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
