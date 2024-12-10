<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CustomerEoi;
use App\Services\CustomerEoiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CustomerEoiControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get the service instance.
     *
     * @return CustomerEoiService
     */
    public function getService()
    {
        return new CustomerEoiService();
    }

    /**
     * Test if all DDQ Existings can be listed.
     *
     * @return void
     */
    public function test_it_can_list_all_ddq_existings()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerEoi::factory()->count(3)->create();

        $response = $this->getJson('/api/customer-eois');

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
        $customerEoi = CustomerEoi::factory()->create();

        $response = $this->getJson("/api/customer-eois/{$customerEoi->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $customerEoi->id,
                    'user_id' => $customerEoi->user_id,
                    'customer_id' => $customerEoi->customer_id,
                    'customer_site_id' => $customerEoi->customer_site_id,
                    'file_path' => $customerEoi->file_path,
                    'status' => $customerEoi->status,
                ],
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
        $customerEoi = CustomerEoi::factory()->create();

        $response = $this->deleteJson("/api/customer-eois/{$customerEoi->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('customer_ddq_existings', [
            'id' => $customerEoi->id,
        ]);
    }
}
