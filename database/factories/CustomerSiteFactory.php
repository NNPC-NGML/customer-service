<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSite>
 */
class CustomerSiteFactory extends Factory
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
            'site_address' => $this->faker->address,
            'ngml_zone_id' => 1,
            'site_name' => $this->faker->company,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'site_contact_person_name' => $this->faker->name,
            'site_contact_person_email' => $this->faker->safeEmail,
            'site_contact_person_phone_number' => $this->faker->phoneNumber,
            'site_contact_person_signature' => null,
            'site_existing_status' => false,
            'created_by_user_id' => User::factory(),
            'status' => true,
        ];
    }
}
