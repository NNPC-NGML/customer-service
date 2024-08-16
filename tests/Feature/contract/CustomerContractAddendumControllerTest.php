<?php

namespace Tests\Feature\contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractAddendum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerContractAddendumControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_addendums()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerContractAddendum::factory(3)->create();

        $response = $this->getJson('/api/contract-addendums');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_new_addendum()
    {
        $this->actingAsAuthenticatedTestUser();
        $customer = User::factory()->create();
        $customerSite = User::factory()->create();
        $parentContract = CustomerContract::factory()->create();
        $childContract = CustomerContract::factory()->create();

        $addendumData = [
            'customer_id' => $customer->id,
            'customer_site_id' => $customerSite->id,
            'parent_contract_id' => $parentContract->id,
            'child_contract_id' => $childContract->id,
            'status' => true,
        ];

        $response = $this->postJson('/api/contract-addendums', $addendumData);

        $response->assertStatus(201)
            ->assertJsonFragment($addendumData);
    }

    public function test_show_returns_specific_addendum()
    {
        $this->actingAsAuthenticatedTestUser();
        $addendum = CustomerContractAddendum::factory()->create();

        $response = $this->getJson("/api/contract-addendums/{$addendum->id}");

        $response->assertStatus(200);
        // ->assertJson($addendum->toArray());
    }

    public function test_update_modifies_addendum()
    {
        $this->actingAsAuthenticatedTestUser();
        $addendum = CustomerContractAddendum::factory()->create();
        $newCustomer = User::factory()->create();

        $updateData = [
            'customer_id' => $newCustomer->id,
            'status' => false,
        ];

        $response = $this->putJson("/api/contract-addendums/{$addendum->id}", $updateData);

        $response->assertStatus(200);
    }

    public function test_destroy_deletes_addendum()
    {
        $this->actingAsAuthenticatedTestUser();
        $addendum = CustomerContractAddendum::factory()->create();

        $response = $this->deleteJson("/api/contract-addendums/{$addendum->id}");

        $response->assertStatus(204);
    }
}
