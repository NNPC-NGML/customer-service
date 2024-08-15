<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContractSection;
use App\Models\CustomerContractTemplate;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractSectionService;

class CustomerContractSectionServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    private CustomerContractSectionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractSectionService();
        $this->user = User::factory()->create();
        CustomerContractTemplate::factory()->create();
    }

    public function testCreate()
    {
        $contract = CustomerContractTemplate::factory()->create();
        $data = [
            'customer_id' => $this->user->id,
            'customer_site_id' => 1,
            'contract_id' => $contract->id,
            'title_id' => 'TITLE001',
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $section = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractSection::class, $section);
        $this->assertEquals($data['customer_id'], $section->customer_id);
        $this->assertEquals($data['customer_site_id'], $section->customer_site_id);
        $this->assertEquals($data['contract_id'], $section->contract_id);
        $this->assertEquals($data['title_id'], $section->title_id);
        $this->assertEquals($data['created_by_user_id'], $section->created_by_user_id);
        $this->assertEquals($data['status'], $section->status);
    }

    public function testUpdate()
    {
        $section = CustomerContractSection::factory()->create();
        $newData = [
            'title_id' => 'NEWTITLE001',
            'status' => false,
        ];

        $result = $this->service->update($section, $newData);

        $this->assertTrue($result);
        $this->assertEquals($newData['title_id'], $section->fresh()->title_id);
        $this->assertEquals($newData['status'], $section->fresh()->status);
    }

    public function testDelete()
    {
        $section = CustomerContractSection::factory()->create();

        $result = $this->service->delete($section);

        $this->assertTrue($result);
        $this->assertNull(CustomerContractSection::find($section->id));
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

        $results = $this->service->getAll();

        $this->assertCount(3, $results);
        $this->assertInstanceOf(CustomerContractSection::class, $results->first());
    }

    public function testValidationFailure()
    {
        $this->expectException(ValidationException::class);

        $invalidData = [
            'customer_id' => 'not_an_integer',
            'customer_site_id' => 1,
            'contract_id' => 1,
            'title_id' => 'TITLE001',
            'created_by_user_id' => 1,
            'status' => true,
        ];

        $this->service->create($invalidData);
    }
}
