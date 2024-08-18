<?php

namespace Tests\Feature\contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractSignature;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerContractSignatureControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_sign_creates_new_signature()
    {
        $this->actingAsAuthenticatedTestUser();
        $contract = CustomerContract::factory()->create();
        $customer = User::factory()->create();

        $signatureData = [
            'contract_id' => $contract->id,
            'customer_id' => $customer->id,
            'customer_site_id' => $customer->id,
            'signature' => 'John Doe',
            'title' => 'CEO',
            'signature_type' => 'user_id',
        ];

        $response = $this->postJson('/api/contracts/sign', $signatureData);

        $response->assertStatus(201)
            ->assertJsonFragment($signatureData);
    }

    public function test_show_returns_specific_signature()

    {
        $this->actingAsAuthenticatedTestUser();
        $signature = CustomerContractSignature::factory()->create();

        $response = $this->getJson("/api/contracts/signatures/{$signature->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Contract signature retrieved successfully.',
                'data' => $signature->toArray(),
            ]);
    }
}
