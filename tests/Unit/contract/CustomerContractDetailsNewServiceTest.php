<?php

namespace Tests\Unit\Contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContractSection;
use App\Models\CustomerContractTemplate;
use App\Models\CustomerContractDetailsNew;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractDetailsNewService;

class CustomerContractDetailsNewServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerContractDetailsNewService $service;
    protected $user;


    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractDetailsNewService();
        $this->user = User::factory()->create();
        CustomerContractTemplate::factory()->create();
        CustomerContractSection::factory()->create();
    }

    public function testCreate()
    {
        $section = CustomerContractSection::factory()->create();
        $template = CustomerContractTemplate::factory()->create();

        $data = [
            "contract_id" => $section->id,
            'details' => 'Test content',
            'section_id' => $section->id,
            'customer_id' => $this->user->id,
            'created_by_user_id' => $this->user->id,
            'customer_site_id' => $this->user->id,
            'status' => true,
        ];

        $detail = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractDetailsNew::class, $detail);
        $this->assertEquals($data['section_id'], $detail->section_id);
        $this->assertEquals($data['created_by_user_id'], $detail->created_by_user_id);
        $this->assertEquals($data['status'], $detail->status);
    }

    public function testUpdate()
    {
        $detail = CustomerContractDetailsNew::factory()->create();
        $newData = [
            'details' => 'Updated content',
            'status' => false,
        ];

        $result = $this->service->update($detail, $newData);

        $this->assertTrue($result);
        $this->assertEquals($newData['details'], $detail->fresh()->details);
        $this->assertEquals($newData['status'], $detail->fresh()->status);
    }

    public function testDelete()
    {
        $detail = CustomerContractDetailsNew::factory()->create();

        $result = $this->service->delete($detail);

        $this->assertTrue($result);
        $this->assertNull(CustomerContractDetailsNew::find($detail->id));
    }

    public function testGetById()
    {
        $detail = CustomerContractDetailsNew::factory()->create();

        $result = $this->service->getById($detail->id);

        $this->assertInstanceOf(CustomerContractDetailsNew::class, $result);
        $this->assertEquals($detail->id, $result->id);
    }

    public function testGetAll()
    {
        CustomerContractDetailsNew::factory()->count(3)->create();

        $results = $this->service->getAll();

        $this->assertCount(3, $results);
        $this->assertInstanceOf(CustomerContractDetailsNew::class, $results->first());
    }

    public function testValidationFailure()
    {
        $this->expectException(ValidationException::class);

        $invalidData = [
            'details' => '',  // Invalid because content is required
            'section_id' => 1,
            'template_id' => 1,
            'created_by_user_id' => 1,
            'status_id' => true,
        ];

        $this->service->create($invalidData);
    }
}
