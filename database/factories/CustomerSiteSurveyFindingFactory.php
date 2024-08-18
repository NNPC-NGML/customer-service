<?php

namespace Database\Factories;

use App\Models\CustomerSite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSiteSurveyFinding>
 */
class CustomerSiteSurveyFindingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'customer_site_id' => CustomerSite::factory()->create(),
            'file_path' => $this->faker->filePath(),
            'created_by_user_id' => User::factory(),
            'status' => $this->faker->boolean(),
        ];
    }
}
