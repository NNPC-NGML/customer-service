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

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractService();
    }

    public function test_to_create()
    {
        $user = User::factory()->create();

        $data = [
            'customer_id' => 1,
            'customer_site_id' => 1,
            'contract_type_id' => 1,
            'created_by_user_id' => $user->id,
            'before_erp' => true,
            'status' => true,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContract::class, $result);
        $this->assertEquals($data['customer_id'], $result->customer_id);
        $this->assertEquals($data['customer_site_id'], $result->customer_site_id);
        $this->assertEquals($data['contract_type_id'], $result->contract_type_id);
        $this->assertEquals($data['created_by_user_id'], $result->created_by_user_id);
        $this->assertEquals($data['before_erp'], $result->before_erp);
        $this->assertEquals($data['status'], $result->status);
    }

    public function test_to_update()
    {
        $contract = CustomerContract::factory()->create();
        $user = User::factory()->create();

        $data = [
            'contract_type_id' => 2,
            'created_by_user_id' => $user->id,
            'before_erp' => false,
            'status' => false,
        ];

        $result = $this->service->update($contract->id, $data);

        $this->assertEquals($data['contract_type_id'], $result->contract_type_id);
        $this->assertEquals($data['created_by_user_id'], $result->created_by_user_id);
        $this->assertEquals($data['before_erp'], $result->before_erp);
        $this->assertEquals($data['status'], $result->status);
    }

    public function test_to_delete()
    {
        $contract = CustomerContract::factory()->create();

        $result = $this->service->delete($contract->id);

        $this->assertTrue($result);
        $this->assertNull(CustomerContract::find($contract->id));
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
        $this->assertInstanceOf(CustomerContract::class, $result->first());
    }

    public function testCreateWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'customer_id' => 'invalid',
            'customer_site_id' => 'invalid',
            'contract_type_id' => 'invalid',
            'created_by_user_id' => 999,
            'before_erp' => 'invalid',
            'status' => 'invalid',
        ];

        $this->service->create($data);
    }
}
