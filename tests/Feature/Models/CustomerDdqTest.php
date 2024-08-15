<?php

namespace Tests\Feature\Models;

use App\Models\CustomerDdq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerDdqTest extends TestCase
{

    // use RefreshDatabase;

    // /** @test */
    // public function it_has_a_factory()
    // {
    //     // Assert that the model can be created using its factory
    //     $customerDdq = CustomerDdq::factory()->create();

    //     $this->assertDatabaseHas('customer_ddqs', [
    //         'id' => $customerDdq->id,
    //     ]);
    // }

    // /** @test */
    // public function it_has_guarded_properties()
    // {
    //     $model = new CustomerDdq();

    //     // Ensure 'id', 'created_at', and 'updated_at' are guarded
    //     $this->assertTrue($model->isGuarded('id'));
    //     $this->assertTrue($model->isGuarded('created_at'));
    //     $this->assertTrue($model->isGuarded('updated_at'));
    // }

    // /** @test */
    // public function it_can_be_mass_assigned()
    // {
    //     $customerDdq = CustomerDdq::create([
    //         // Assuming your table has some columns for mass assignment
    //         // Add appropriate columns here
    //         'data' => 'Some data',
    //         'customer_id' => 1,
    //         'customer_site_id' => 1,
    //         'group_id' => 1,
    //         'subgroup_id' => 1,
    //         'document_type' => 'string',
    //         'created_by_user_id' => 1,
    //     ]);

    //     // Check if the instance was created in the database
    //     $this->assertDatabaseHas('customer_ddqs', [
    //         // Add appropriate columns here
    //         // 'some_column' => 'some_value',
    //         'data' => 'Some data',
    //         'customer_id' => 1,
    //         'customer_site_id' => 1,
    //         'group_id' => 1,
    //         'subgroup_id' => 1,
    //         'document_type' => 'string',
    //         'created_by_user_id' => 1,
    //     ]);
    // }

    // /** @test */
    // public function it_can_be_updated()
    // {
    //     $customerDdq = CustomerDdq::factory()->create([
    //         // Set initial values if necessary
    //         // 'some_column' => 'old_value',
    //     ]);

    //     $customerDdq->update([
    //         // Update values
    //         // 'some_column' => 'new_value',
    //     ]);

    //     $this->assertDatabaseHas('customer_ddqs', [
    //         // Ensure updated values are present
    //         // 'some_column' => 'new_value',
    //     ]);
    // }

    // /** @test */
    // public function it_can_be_deleted()
    // {
    //     $customerDdq = CustomerDdq::factory()->create();

    //     $customerDdq->delete();

    //     $this->assertDatabaseMissing('customer_ddqs', [
    //         'id' => $customerDdq->id,
    //     ]);
    // }
}
