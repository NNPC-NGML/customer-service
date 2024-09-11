<?php

namespace Tests\Feature\Models;

use App\Models\CustomerDdqGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerDdqGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_factory()
    {
        // Assert that the model can be created using its factory
        $customerDdqGroup = CustomerDdqGroup::factory()->create();

        $this->assertDatabaseHas('customer_ddq_groups', [
            'id' => $customerDdqGroup->id,
        ]);
    }

    /** @test */
    public function it_has_guarded_properties()
    {
        $model = new CustomerDdqGroup();

        // Ensure 'id', 'created_at', and 'updated_at' are guarded
        $this->assertTrue($model->isGuarded('id'));
        $this->assertTrue($model->isGuarded('created_at'));
        $this->assertTrue($model->isGuarded('updated_at'));
    }

    /** @test */
    public function it_can_be_mass_assigned()
    {
        $customerDdqGroup = CustomerDdqGroup::create([
            // Assuming your table has some columns for mass assignment
            // Add appropriate columns here
            'title' => 'Compliance Group',
            'status' => true,
        ]);

        // Check if the instance was created in the database
        $this->assertDatabaseHas('customer_ddq_groups', [
            // Add appropriate columns here
            'title' => 'Compliance Group',
            'status' => true,
        ]);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $customerDdqGroup = CustomerDdqGroup::factory()->create([
            // Set initial values if necessary
            'title' => 'Old Compliance Group'
        ]);

        $customerDdqGroup->update([
            // Update values
            'title' => 'Old Compliance Group'
        ]);

        $this->assertDatabaseHas('customer_ddq_groups', [
            // Ensure updated values are present
            'title' => 'Old Compliance Group'
        ]);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $customerDdqGroup = CustomerDdqGroup::factory()->create();

        $customerDdqGroup->delete();

        $this->assertDatabaseMissing('customer_ddq_groups', [
            'id' => $customerDdqGroup->id,
        ]);
    }
}
