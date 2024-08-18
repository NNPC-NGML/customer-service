<?php

namespace Database\Factories;

use App\Models\CustomerSite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSurveyCapex>
 */
class CustomerSurveyCapexFactory extends Factory
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
            'customer_site_id' => CustomerSite::factory(),
            'customer_proposed_daily_consumption' => $this->faker->numberBetween(100, 1000) . ' SCUF',
            'project_cost_in_naira' => $this->faker->numberBetween(1000000, 10000000),
            'gas_rate_per_scuf_in_naira' => $this->faker->numberBetween(100, 300),
            'dollar_rate' => $this->faker->numberBetween(400, 600),
            'capex_file_path' => $this->faker->filePath(),
            'created_by_user_id' => User::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
