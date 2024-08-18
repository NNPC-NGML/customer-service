<?php

namespace Tests\Unit\Services\Contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContractDetailsOld;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractDetailsOldService;

class CustomerContractDetailsOldServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerContractDetailsOldService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractDetailsOldService();
    }

    public function testCreate()
    {
        $user = User::factory()->create();

        $data = [
            'contract_id' => 1,
            'file_path' => '/path/to/file.pdf',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'status' => true,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractDetailsOld::class, $result);
        $this->assertEquals($data['contract_id'], $result->contract_id);
        $this->assertEquals($data['file_path'], $result->file_path);
        $this->assertEquals($data['customer_id'], $result->customer_id);
        $this->assertEquals($data['customer_site_id'], $result->customer_site_id);
        $this->assertEquals($data['created_by_user_id'], $result->created_by_user_id);
        $this->assertEquals($data['status'], $result->status);
    }

    public function testCreateWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'contract_id' => 'invalid', // Should be an integer
            'file_path' => '',
            'customer_id' => 'invalid', // Should be an integer
            'customer_site_id' => 'invalid', // Should be an integer
            'created_by_user_id' => 999, // Non-existent user ID
            'status' => 'invalid', // Should be a boolean
        ];

        $this->service->create($data);
    }

    public function testGetById()
    {
        $contractDetails = CustomerContractDetailsOld::factory()->create();

        $result = $this->service->getById($contractDetails->id);

        $this->assertInstanceOf(CustomerContractDetailsOld::class, $result);
        $this->assertEquals($contractDetails->id, $result->id);
    }

    public function testGetByIdWithNonExistentId()
    {
        $result = $this->service->getById(999);

        $this->assertNull($result);
    }

    public function testGetAll()
    {
        CustomerContractDetailsOld::factory()->count(3)->create();

        $result = $this->service->getAll();

        $this->assertCount(3, $result);
        $this->assertInstanceOf(CustomerContractDetailsOld::class, $result->first());
    }
}
