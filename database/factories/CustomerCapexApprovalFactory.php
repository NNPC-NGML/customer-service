<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomercapexApproval>
 */
class CustomerCapexApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\User::factory(),
            'customer_site_id' => \App\Models\CustomerSite::factory(),
            'approval_type_id' => 1, // Assuming this is a valid ID, adjust as necessary
            'approved_by_user_id' => \App\Models\User::factory(),
            'comment' => $this->faker->sentence,
            'status' => $this->faker->boolean,
        ];
    }
}
