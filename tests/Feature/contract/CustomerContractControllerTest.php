<?php

namespace Tests\Feature\contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerContractControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_contracts()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerContract::factory(3)->create();

        $response = $this->getJson('/api/contracts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_new_contract()
    {
        $this->actingAsAuthenticatedTestUser();
        $customer = User::factory()->create();
        $contractType = CustomerContractType::factory()->create();

        $contractData = [
            'customer_id' => $customer->id,
            'customer_site_id' => $customer->id,
            'contract_type_id' => $contractType->id,
            'before_erp' => true,
            'status' => true,
        ];

        $response = $this->postJson('/api/contracts', $contractData);

        $response->assertStatus(201)
            ->assertJsonFragment($contractData);
    }

    public function test_show_returns_specific_contract()
    {
        $this->actingAsAuthenticatedTestUser();
        $contract = CustomerContract::factory()->create();

        $response = $this->getJson("/api/contracts/{$contract->id}");

        $response->assertStatus(200)
            ->assertJson($contract->toArray());
    }

    public function test_update_modifies_contract()
    {
        $this->actingAsAuthenticatedTestUser();
        $contract = CustomerContract::factory()->create();
        $newCustomer = User::factory()->create();

        $updateData = [
            'customer_id' => $newCustomer->id,
            'status' => false,
        ];

        $response = $this->putJson("/api/contracts/{$contract->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
    }

    public function test_destroy_deletes_contract()
    {
        $this->actingAsAuthenticatedTestUser();
        $contract = CustomerContract::factory()->create();

        $response = $this->deleteJson("/api/contracts/{$contract->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customer_contracts', ['id' => $contract->id]);
    }
}
