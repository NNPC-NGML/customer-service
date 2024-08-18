<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractSignature;

use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\ContractSignatureService;

class ContractSignatureServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContractSignatureService();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_create_signature()
    {
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

        $signature = $this->service->createSignature($signatureData);

        $this->assertInstanceOf(CustomerContractSignature::class, $signature);
        $this->assertDatabaseHas('customer_contract_signatures', [
            'contract_id' => $contract->id,
            'customer_id' => $customer->id,
            'signature' => 'John Doe',
            'title' => 'CEO',
        ]);
    }

    public function test_create_signature_fails_validation()
    {
        $this->expectException(ValidationException::class);

        // Provide invalid data
        $invalidData = [
            'contract_id' => 999,
            'customer_id' => 999,
            'customer_site_id' => 999,
            'signature' => '',
            'title' => '',
            'signature_type' => 'invalid_type',
        ];

        $this->service->createSignature($invalidData);
    }

    public function test_get_signature_by_id()
    {
        $signature = CustomerContractSignature::factory()->create();

        $retrievedSignature = $this->service->getSignatureById($signature->id);

        $this->assertInstanceOf(CustomerContractSignature::class, $retrievedSignature);
        $this->assertEquals($signature->id, $retrievedSignature->id);
    }
}
