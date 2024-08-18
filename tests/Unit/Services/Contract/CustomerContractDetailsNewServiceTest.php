<?php

namespace Tests\Unit\Services\Contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContractDetailsNew;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractDetailsNewService;

class CustomerContractDetailsNewServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerContractDetailsNewService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractDetailsNewService();
    }

    public function test_to_reate()
    {
        $user = User::factory()->create();

        $data = [
            'contract_id' => 1,
            'details' => 'Sample contract details',
            'section_id' => 1,
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'status' => true,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractDetailsNew::class, $result);
        $this->assertEquals($data['contract_id'], $result->contract_id);
        $this->assertEquals($data['details'], $result->details);
        $this->assertEquals($data['section_id'], $result->section_id);
        $this->assertEquals($data['customer_id'], $result->customer_id);
        $this->assertEquals($data['customer_site_id'], $result->customer_site_id);
        $this->assertEquals($data['created_by_user_id'], $result->created_by_user_id);
        $this->assertEquals($data['status'], $result->status);
    }

    public function test_to_create_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'contract_id' => 'invalid', // Should be an integer
            'details' => '',
            'section_id' => 'invalid', // Should be an integer
            'customer_id' => 'invalid', // Should be an integer
            'customer_site_id' => 'invalid', // Should be an integer
            'created_by_user_id' => 999, // Non-existent user ID
            'status' => 'invalid', // Should be a boolean
        ];

        $this->service->create($data);
    }

    public function test_to_get_by_id()
    {
        $contractDetails = CustomerContractDetailsNew::factory()->create();

        $result = $this->service->getById($contractDetails->id);

        $this->assertInstanceOf(CustomerContractDetailsNew::class, $result);
        $this->assertEquals($contractDetails->id, $result->id);
    }

    public function test_get_by_id_with_non_existent_id()
    {
        $result = $this->service->getById(999);

        $this->assertNull($result);
    }

    public function test_to_get_all()
    {
        CustomerContractDetailsNew::factory()->count(3)->create();

        $result = $this->service->getAll();

        $this->assertCount(3, $result);
        $this->assertInstanceOf(CustomerContractDetailsNew::class, $result->first());
    }
}
