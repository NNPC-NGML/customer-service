<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\CustomerDdqController;
use App\Models\CustomerDdq;
use App\Models\CustomerDdqGroup;
use App\Models\CustomerDdqSubGroup;
use App\Services\DDQService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CustomerDdqControllerTest
 *
 * This class contains feature tests for the Customer DDQ management functionality.
 *
 * @package Tests\Feature\Http\Controllers
 */
class CustomerDdqControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var DDQService The service for handling DDQ-related operations.
     */
    protected DDQService $ddqService;

    /**
     * Set up the test environment.
     *
     * Initializes the DDQ service and sets up an authenticated test user.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->ddqService = new DDQService();
    }

    /**
     * Test if a DDQ can be created.
     *
     * @return void
     */
    public function test_can_create_ddq()
    {
        $group = CustomerDdqGroup::factory()->create();
        $subgroup = CustomerDdqSubGroup::factory()->create(['customer_ddq_group_id' => $group->id]);

        $data = [
            'json_form' => [
                'group_id' => $group->id,
                'subgroup_id' => $subgroup->id,
                'customer_id' => 1,
                'customer_site_id' => 1,
                'created_by_user_id' => 1,
                'field_1' => [
                    'data' => 'Some data',
                    'document_type' => 'string',
                ],
            ]
        ];

        $this->ddqService->create($data);

        $this->assertDatabaseHas('customer_ddqs', [
            'data' => 'Some data',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'group_id' => $group->id,
            'subgroup_id' => $subgroup->id,
            'document_type' => 'string',
            'created_by_user_id' => 1,
        ]);
    }

    /**
     * Test if a DDQ can be viewed.
     *
     * @return void
     */
    public function test_can_view_ddq()
    {
        $this->actingAsAuthenticatedTestUser();
        $ddq = CustomerDdq::factory()->create(['data' => 'View Data']);

        // $response = $this->get('/api/ddqs/view/'.$ddq->customer_id.'/'.$ddq->customer_site_id.'/'.$ddq->group_id.'/'.$ddq->subgroup_id);
        $response = $this->get("/api/ddqs/view/{$ddq->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'data' => 'View Data'
            ]);
    }

    /**
     * Test if a DDQ can be updated.
     *
     * @return void
     */
    public function test_can_update_ddq()
    {
        $ddq = CustomerDdq::factory()->create(['data' => 'Old Data']);

        $data = [
            'json_form' => [
                'group_id' => $ddq->group_id,
                'subgroup_id' => $ddq->subgroup_id,
                'customer_id' => $ddq->customer_id,
                'customer_site_id' => $ddq->customer_site_id,
                'created_by_user_id' => $ddq->created_by_user_id,
                'field_1' => [
                    'document_type' => 'string',
                    'data' => 'Updated Data'
                ],
            ]
        ];

        $this->ddqService->update($data);

        $this->assertDatabaseHas('customer_ddqs', [
            'data' => 'Updated Data'
        ]);
    }

    /**
     * Test if a DDQ can be deleted.
     *
     * @return void
     */
    public function test_can_delete_ddq()
    {
        $ddq = CustomerDdq::factory()->create(['data' => 'Delete Data']);

        (new CustomerDdqController($this->ddqService))->destroy($ddq->id);

        $this->assertDatabaseMissing('customer_ddqs', ['data' => 'Delete Data']);
    }

    /**
     * Test if a DDQ can be approved.
     *
     * @return void
     */
    public function test_can_approve_ddq()
    {
        $this->actingAsAuthenticatedTestUser();
        $ddq = CustomerDdq::factory()->create(['status' => 'pending']);

        $this->post('/api/ddqs/approve/'.$ddq->id);

        $this->assertDatabaseHas('customer_ddqs', ['status' => 'approved']);
    }

    /**
     * Test if a DDQ can be declined.
     *
     * @return void
     */
    public function test_can_decline_ddq()
    {
        $this->actingAsAuthenticatedTestUser();
        $ddq = CustomerDdq::factory()->create(['status' => 'pending']);

        $this->post('/api/ddqs/decline/'.$ddq->id);

        $this->assertDatabaseHas('customer_ddqs', ['status' => 'declined']);
    }
}
