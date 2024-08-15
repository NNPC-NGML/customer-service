<?php

namespace Tests\Unit\Contract;

use Tests\TestCase;
use App\Models\CustomerContractType;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractTypeService;

class CustomerContractTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerContractTypeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractTypeService();
    }

    public function testCreate()
    {
        $data = [
            'title' => 'Test Contract Type',
            'status' => 1,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractType::class, $result);
        $this->assertDatabaseHas('customer_contract_types', $data);
    }

    public function testUpdate()
    {
        $contractType = CustomerContractType::factory()->create();
        $data = ['title' => 'Updated Contract Type'];

        $result = $this->service->update($contractType, $data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('customer_contract_types', ['id' => $contractType->id, 'title' => 'Updated Contract Type']);
    }

    public function testDelete()
    {
        $contractType = CustomerContractType::factory()->create();

        $result = $this->service->delete($contractType);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('customer_contract_types', ['id' => $contractType->id]);
    }

    public function testGetById()
    {
        $contractType = CustomerContractType::factory()->create();

        $result = $this->service->getById($contractType->id);

        $this->assertInstanceOf(CustomerContractType::class, $result);
        $this->assertEquals($contractType->id, $result->id);
    }

    public function testGetAll()
    {
        CustomerContractType::factory()->count(3)->create();

        $result = $this->service->getAll();

        $this->assertCount(3, $result);
    }

    public function testValidationFailsOnCreate()
    {
        $this->expectException(ValidationException::class);

        $this->service->create([]);
    }

    public function testValidationFailsOnUpdate()
    {
        $this->expectException(ValidationException::class);

        $contractType = CustomerContractType::factory()->create();
        $this->service->update($contractType, ['status' => 'invalid']);
    }

    public function testPartialUpdate()
    {
        $contractType = CustomerContractType::factory()->create();
        $data = ['status' => 0];

        $result = $this->service->update($contractType, $data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('customer_contract_types', ['id' => $contractType->id, 'status' => 0]);
    }
}
