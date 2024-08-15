<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CustomerDdqGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CustomerDdqGroupControllerTest
 *
 * This class contains feature tests for the Customer DDQ Group management.
 *
 * @package Tests\Feature\Http\Controllers
 */
class CustomerDdqGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a DDQ group can be created.
     *
     * @return void
     */
    public function it_can_create_a_ddq_group()
    {
        $this->actingAsAuthenticatedTestUser();
        $response = $this->postJson('/api/ddq-groups', [
            'title' => 'Compliance Group',
            'status' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customer_ddq_groups', [
            'title' => 'Compliance Group',
            'status' => true,
        ]);
    }

    /**
     * Test if all DDQ groups can be listed.
     *
     * @return void
     */
    public function it_can_list_all_ddq_groups()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerDdqGroup::factory()->count(3)->create();

        $response = $this->getJson('/api/ddq-groups');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /**
     * Test if a single DDQ group can be viewed.
     *
     * @return void
     */
    public function it_can_view_a_single_ddq_group()
    {
        $this->actingAsAuthenticatedTestUser();
        $group = CustomerDdqGroup::factory()->create();

        $response = $this->getJson("/api/ddq-groups/{$group->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $group->id,
                     'title' => $group->title,
                 ]);
    }

    /**
     * Test if a DDQ group can be updated.
     *
     * @return void
     */
    public function it_can_update_a_ddq_group()
    {
        $this->actingAsAuthenticatedTestUser();
        $group = CustomerDdqGroup::factory()->create([
            'title' => 'Old Title',
        ]);

        $response = $this->putJson("/api/ddq-groups/{$group->id}", [
            'title' => 'New Title',
            'status' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customer_ddq_groups', [
            'id' => $group->id,
            'title' => 'New Title',
            'status' => false,
        ]);
    }

    /**
     * Test if a DDQ group can be deleted.
     *
     * @return void
     */
    public function it_can_delete_a_ddq_group()
    {
        $this->actingAsAuthenticatedTestUser();
        $group = CustomerDdqGroup::factory()->create();

        $response = $this->deleteJson("/api/ddq-groups/{$group->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customer_ddq_groups', [
            'id' => $group->id,
        ]);
    }
}
