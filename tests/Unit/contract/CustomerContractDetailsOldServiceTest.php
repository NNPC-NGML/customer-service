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
    protected $user;

    private CustomerContractDetailsOldService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractDetailsOldService();
        $this->user = User::factory()->create();
    }

    public function testCreate()
    {
        $data = [
            'contract_id' => 1,
            'file_path' => 'path/to/file.pdf',
            'customer_id' => $this->user->id,
            'customer_site_id' => 1,
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractDetailsOld::class, $result);
        $this->assertDatabaseHas('customer_contract_details_olds', $data);
    }

    public function testUpdate()
    {
        $detail = CustomerContractDetailsOld::factory()->create();
        $data = ['file_path' => 'new/path/to/file.pdf'];

        $result = $this->service->update($detail, $data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('customer_contract_details_olds', ['id' => $detail->id, 'file_path' => 'new/path/to/file.pdf']);
    }

    public function testDelete()
    {
        $detail = CustomerContractDetailsOld::factory()->create();

        $result = $this->service->delete($detail);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('customer_contract_details_olds', ['id' => $detail->id]);
    }

    public function testGetById()
    {
        $detail = CustomerContractDetailsOld::factory()->create();

        $result = $this->service->getById($detail->id);

        $this->assertInstanceOf(CustomerContractDetailsOld::class, $result);
        $this->assertEquals($detail->id, $result->id);
    }

    public function testGetAll()
    {
        CustomerContractDetailsOld::factory()->count(3)->create();

        $result = $this->service->getAll();

        $this->assertCount(3, $result);
    }

    public function testValidationFails()
    {
        $this->expectException(ValidationException::class);

        $this->service->create([]);
    }
}
