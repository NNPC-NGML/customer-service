<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CustomerDdqSubGroup;
use App\Models\CustomerDdqGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDdqSubGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_ddq_subgroup()
    {
        $this->actingAsAuthenticatedTestUser();
        $group = CustomerDdqGroup::factory()->create();

        $response = $this->postJson('/api/ddq-subgroups', [
            'title' => 'Compliance Subgroup',
            'customer_ddq_group_id' => $group->id,
            'status' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customer_ddq_sub_groups', [
            'title' => 'Compliance Subgroup',
            'customer_ddq_group_id' => $group->id,
            'status' => true,
        ]);
    }

    /** @test */
    public function it_can_list_all_ddq_subgroups()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerDdqSubGroup::factory()->count(3)->create();

        $response = $this->getJson('/api/ddq-subgroups');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_view_a_single_ddq_subgroup()
    {
        $this->actingAsAuthenticatedTestUser();
        $subgroup = CustomerDdqSubGroup::factory()->create();

        $response = $this->getJson("/api/ddq-subgroups/{$subgroup->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $subgroup->id,
                     'title' => $subgroup->title,
                 ]);
    }

    /** @test */
    public function it_can_update_a_ddq_subgroup()
    {
        $this->actingAsAuthenticatedTestUser();
        $subgroup = CustomerDdqSubGroup::factory()->create([
            'title' => 'Old Subgroup',
        ]);

        $response = $this->putJson("/api/ddq-subgroups/{$subgroup->id}", [
            'title' => 'New Subgroup',
            'customer_ddq_group_id' => $subgroup->customer_ddq_group_id,
            'status' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customer_ddq_sub_groups', [
            'id' => $subgroup->id,
            'title' => 'New Subgroup',
            'customer_ddq_group_id' => $subgroup->customer_ddq_group_id,
            'status' => false,
        ]);
    }

    /** @test */
    public function it_can_delete_a_ddq_subgroup()
    {
        $this->actingAsAuthenticatedTestUser();
        $subgroup = CustomerDdqSubGroup::factory()->create();

        $response = $this->deleteJson("/api/ddq-subgroups/{$subgroup->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customer_ddq_sub_groups', [
            'id' => $subgroup->id,
        ]);
    }
}
