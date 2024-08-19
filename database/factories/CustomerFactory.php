<?php

namespace Database\Factories;

use App\Models\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Customer::class;

    public function definition()
    {
        return [
            'company_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'created_by_user_id' => \App\Models\User::factory(),
            'status' => true,
            'created_at' => now(),
           'updated_at' => now(),
        ];
    }
}
