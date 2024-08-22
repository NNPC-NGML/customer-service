<?php

namespace Tests\Feature\Models;

use App\Models\CustomerDdqSubGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerDdqSubGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_factory()
    {
        // Assert that the model can be created using its factory
        $customerDdqSubGroup = CustomerDdqSubGroup::factory()->create();

        $this->assertDatabaseHas('customer_ddq_sub_groups', [
            'id' => $customerDdqSubGroup->id,
        ]);
    }

    /** @test */
    public function it_has_guarded_properties()
    {
        $model = new CustomerDdqSubGroup();

        // Ensure 'id', 'created_at', and 'updated_at' are guarded
        $this->assertTrue($model->isGuarded('id'));
        $this->assertTrue($model->isGuarded('created_at'));
        $this->assertTrue($model->isGuarded('updated_at'));
    }

    /** @test */
    public function it_can_be_mass_assigned()
    {
        $customerDdqSubGroup = CustomerDdqSubGroup::create([
            // Add appropriate columns for mass assignment
            'title' => 'Sample SubGroup Title',
            'customer_ddq_group_id' => 1,
            'status' => true,
        ]);

        // Check if the instance was created in the database
        $this->assertDatabaseHas('customer_ddq_sub_groups', [
            // Add appropriate columns to verify
            'title' => 'Sample SubGroup Title',
        ]);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $customerDdqSubGroup = CustomerDdqSubGroup::factory()->create([
            // Set initial values if necessary
            'title' => 'Old Title',
        ]);

        $customerDdqSubGroup->update([
            // Update values
            'title' => 'New Title',
        ]);

        $this->assertDatabaseHas('customer_ddq_sub_groups', [
            // Ensure updated values are present
            'title' => 'New Title',
        ]);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $customerDdqSubGroup = CustomerDdqSubGroup::factory()->create();

        $customerDdqSubGroup->delete();

        $this->assertDatabaseMissing('customer_ddq_sub_groups', [
            'id' => $customerDdqSubGroup->id,
        ]);
    }
}
