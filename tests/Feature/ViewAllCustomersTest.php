<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;

class ViewAllCustomersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the endpoint to view all customers.
     *
     * @return void
     */
    public function test_view_all_customers()
    {
        // Seed the database with customers
        Customer::factory()->count(5)->create();

        // Call the API endpoint
        $response = $this->getJson('/api/customers');

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }
}