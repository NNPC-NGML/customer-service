<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CustomerDdqExisting;
use App\Services\CustomerDdqExistingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CustomerDdqExistingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get the service instance.
     *
     * @return CustomerDdqExistingService
     */
    public function getService()
    {
        return new CustomerDdqExistingService();
    }

    /**
     * Test if a DDQ Existing can be created.
     *
     * @return void
     */
    public function test_it_can_create_a_ddq_existing()
    {
        $this->actingAsAuthenticatedTestUser();

        // Define the expected data and mock service behavior
        $data = [
            'user_id' => 1,
            'customer_id' => 1,
            'customer_site_id' => 1,
            'file_path' => 'https://example.com/image1.png',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/customer-ddq-existings', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'data' => $data,
            ]);

        $this->assertDatabaseHas('customer_ddq_existings', $data);
    }

    /**
     * Test if all DDQ Existings can be listed.
     *
     * @return void
     */
    public function test_it_can_list_all_ddq_existings()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerDdqExisting::factory()->count(3)->create();

        $response = $this->getJson('/api/customer-ddq-existings');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test if a single DDQ Existing can be viewed.
     *
     * @return void
     */
    public function test_it_can_view_a_single_ddq_existing()
    {
        $this->actingAsAuthenticatedTestUser();
        $ddqExisting = CustomerDdqExisting::factory()->create();

        $response = $this->getJson("/api/customer-ddq-existings/{$ddqExisting->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $ddqExisting->id,
                    'user_id' => $ddqExisting->user_id,
                    'customer_id' => $ddqExisting->customer_id,
                    'customer_site_id' => $ddqExisting->customer_site_id,
                    'file_path' => $ddqExisting->file_path,
                    'status' => $ddqExisting->status,
                ],
            ]);
    }

    /**
     * Test if a DDQ Existing can be updated.
     *
     * @return void
     */
    public function test_it_can_update_a_ddq_existing()
    {
        $this->actingAsAuthenticatedTestUser();
        $ddqExisting = CustomerDdqExisting::factory()->create([
            'status' => 'pending',
        ]);

        $updatedData = [
            'user_id' => $ddqExisting->user_id,
            'customer_id' => $ddqExisting->customer_id,
            'customer_site_id' => $ddqExisting->customer_site_id,
            'file_path' => 'https://example.com/image1.png',
            'status' => 'approved',
        ];

        $response = $this->putJson("/api/customer-ddq-existings/{$ddqExisting->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => $updatedData,
            ]);
    }

    /**
     * Test if a DDQ Existing can be deleted.
     *
     * @return void
     */
    public function test_it_can_delete_a_ddq_existing()
    {
        $this->actingAsAuthenticatedTestUser();
        $ddqExisting = CustomerDdqExisting::factory()->create();

        $response = $this->deleteJson("/api/customer-ddq-existings/{$ddqExisting->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('customer_ddq_existings', [
            'id' => $ddqExisting->id,
        ]);
    }
}
