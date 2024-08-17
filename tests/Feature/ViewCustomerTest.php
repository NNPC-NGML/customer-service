<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;

class ViewCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_customer()
    {
        // Create a customer
        $customer = Customer::factory()->create();

        // Call the API endpoint
        $response = $this->getJson('/api/customers/' . $customer->id);

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'company_name',
            'email',
            'phone_number',
            // Add other expected fields here
        ]);
    }

    public function test_view_non_existent_customer()
    {
        // Call the API endpoint for a non-existent customer
        $response = $this->getJson('/api/customers/9999');

        // Assert the response status and message
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Customer not found.',
        ]);
    }
}
