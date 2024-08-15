<?php

namespace Tests\Unit\Services\Contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use Illuminate\Validation\ValidationException;
use App\Services\Contract\CustomerContractService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerContractServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerContractService $service;
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractService();

        $this->user = User::factory()->create();
    }

    public function testCreate()
    {
        $data = [
            'customer_id' => $this->user->id,
            'customer_site_id' => 1,
            'contract_type_id' => 1,
            'created_by_user_id' => $this->user->id,
            'before_erp' => true,
            'status' => true,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContract::class, $result);
        $this->assertDatabaseHas('customer_contracts', $data);
    }

    public function testUpdate()
    {
        $contract = CustomerContract::factory()->create();
        $data = ['status' => false];

        $result = $this->service->update($contract, $data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('customer_contracts', ['id' => $contract->id, 'status' => false]);
    }

    public function testDelete()
    {
        $contract = CustomerContract::factory()->create();

        $result = $this->service->delete($contract);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('customer_contracts', ['id' => $contract->id]);
    }

    public function testGetById()
    {
        $contract = CustomerContract::factory()->create();

        $result = $this->service->getById($contract->id);

        $this->assertInstanceOf(CustomerContract::class, $result);
        $this->assertEquals($contract->id, $result->id);
    }

    public function testGetAll()
    {
        CustomerContract::factory()->count(3)->create();

        $result = $this->service->getAll();

        $this->assertCount(3, $result);
    }

    public function testValidationFails()
    {
        $this->expectException(ValidationException::class);

        $this->service->create([]);
    }

    public function testPartialUpdate()
    {
        $contract = CustomerContract::factory()->create();
        $data = ['status' => false];

        $result = $this->service->update($contract, $data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('customer_contracts', ['id' => $contract->id, 'status' => false]);
    }
}
