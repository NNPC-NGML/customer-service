<?php

namespace Tests\Unit\Services\Contract;



use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContractSection;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractSectionService;

class CustomerContractSectionServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerContractSectionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractSectionService();
    }

    public function testCreate()
    {
        $user = User::factory()->create();

        $data = [
            'customer_id' => 1,
            'customer_site_id' => 1,
            'contract_id' => 1,
            'title' => 'TITLE001',
            'created_by_user_id' => $user->id,
            'status' => true,
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractSection::class, $result);
        $this->assertEquals($data['customer_id'], $result->customer_id);
        $this->assertEquals($data['customer_site_id'], $result->customer_site_id);
        $this->assertEquals($data['contract_id'], $result->contract_id);
        $this->assertEquals($data['title'], $result->title);
        $this->assertEquals($data['created_by_user_id'], $result->created_by_user_id);
        $this->assertEquals($data['status'], $result->status);
    }



    public function testGetById()
    {
        $section = CustomerContractSection::factory()->create();

        $result = $this->service->getById($section->id);

        $this->assertInstanceOf(CustomerContractSection::class, $result);
        $this->assertEquals($section->id, $result->id);
    }

    public function testGetAll()
    {
        CustomerContractSection::factory()->count(3)->create();

        $result = $this->service->getAll();

        $this->assertCount(3, $result);
        $this->assertInstanceOf(CustomerContractSection::class, $result->first());
    }

    public function testCreateWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'customer_id' => 'invalid',
            'customer_site_id' => 'invalid',
            'contract_id' => 'invalid',
            'title' => '',
            'created_by_user_id' => 999, // Non-existent user ID
            'status' => 'invalid',
        ];

        $this->service->create($data);
    }

    // public function testUpdate()
    // {
    //     $section = CustomerContractSection::factory()->create();
    //     $user = User::factory()->create();

    //     $data = [
    //         'title_id' => 'NEWTITLE001',
    //         'created_by_user_id' => $user->id,
    //         'status' => false,
    //     ];

    //     $result = $this->service->update($section->id, $data);

    //     $this->assertEquals($data['title_id'], $result->title_id);
    //     $this->assertEquals($data['created_by_user_id'], $result->created_by_user_id);
    //     $this->assertEquals($data['status'], $result->status);
    // }

    // public function testDelete()
    // {
    //     $section = CustomerContractSection::factory()->create();

    //     $result = $this->service->delete($section->id);

    //     $this->assertTrue($result);
    //     $this->assertNull(CustomerContractSection::find($section->id));
    // }
}
