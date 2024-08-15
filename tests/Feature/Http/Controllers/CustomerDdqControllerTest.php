<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\CustomerDdqController;
use App\Models\CustomerDdq;
use App\Models\CustomerDdqGroup;
use App\Models\CustomerDdqSubGroup;
use App\Services\DDQService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDdqControllerTest extends TestCase
{
    use RefreshDatabase;

    protected DDQService $ddqService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ddqService = new DDQService();
        $this->actingAsAuthenticatedTestUser();
    }

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

    public function test_can_view_ddq()
    {
        $ddq = CustomerDdq::factory()->create(['data' => 'View Data']);

        $response = $this->get('/api/ddqs/view/'.$ddq->customer_id.'/'.$ddq->customer_site_id.'/'.$ddq->group_id.'/'.$ddq->subgroup_id);

        $response->assertStatus(200)
                 ->assertJson([['data' => 'View Data']]);
    }

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

    public function test_can_delete_ddq()
    {
        $ddq = CustomerDdq::factory()->create(['data' => 'Delete Data']);

        (new CustomerDdqController())->destroy($ddq->id);

        $this->assertDatabaseMissing('customer_ddqs', ['data' => 'Delete Data']);
    }

    public function test_can_approve_ddq()
    {
        $ddq = CustomerDdq::factory()->create(['status' => 'pending']);

        $this->post('/api/ddqs/approve/'.$ddq->id);

        $this->assertDatabaseHas('customer_ddqs', ['status' => 'approved']);
    }

    public function test_can_decline_ddq()
    {
        $ddq = CustomerDdq::factory()->create(['status' => 'pending']);

        $this->post('/api/ddqs/decline/'.$ddq->id);

        $this->assertDatabaseHas('customer_ddqs', ['status' => 'declined']);
    }
}
