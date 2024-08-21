<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers()
    {
        $this->actingAsAuthenticatedTestUser();
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_show_customer()
    {

        $this->actingAsAuthenticatedTestUser();
        $customer = Customer::factory()->create();
        $response = $this->getJson("/api/customers/{$customer->id}");
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $customer->id,
                    'company_name' => $customer->company_name,
                    'email' => $customer->email,
                ],
            ]);
    }

    public function test_show_customer_returns_404_if_not_found()
    {
        $this->actingAsAuthenticatedTestUser();
        $response = $this->getJson('/api/customers/999');

        $response->assertStatus(404);
    }
}
