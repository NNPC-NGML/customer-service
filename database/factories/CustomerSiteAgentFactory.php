<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSiteAgent>
 */
class CustomerSiteAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory()->create()->id,
            'site_address' => $this->faker->address,
            'ngml_zone_id' => $this->faker->numberBetween(1, 10),
            'site_name' => $this->faker->company,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'site_contact_person_name' => $this->faker->name,
            'site_contact_person_email' => $this->faker->unique()->safeEmail,
            'site_contact_person_phone_number' => $this->faker->phoneNumber,
            'site_contact_person_signature' => $this->faker->word,
            'site_existing_status' => $this->faker->boolean,
            'created_by_user_id' => User::factory()->create()->id,
            'status' => $this->faker->boolean,
        ];
    }
}
