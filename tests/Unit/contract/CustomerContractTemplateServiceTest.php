<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

use App\Models\CustomerContractTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractTemplateService;

class CustomerContractTemplateServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    private CustomerContractTemplateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractTemplateService();
        $this->user = User::factory()->create();
    }

    public function testCreate()
    {
        $data = [
            'title' => 'Test Template',
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $template = $this->service->create($data);

        $this->assertInstanceOf(CustomerContractTemplate::class, $template);
        $this->assertEquals($data['title'], $template->title);
        $this->assertEquals($data['created_by_user_id'], $template->created_by_user_id);
        $this->assertEquals($data['status'], $template->status);
    }

    public function testUpdate()
    {
        $template = CustomerContractTemplate::factory()->create();
        $newData = [
            'title' => 'Updated Title',
            'created_by_user_id' => $template->created_by_user_id,
            'status' => $template->status
        ];

        $result = $this->service->update($template, $newData);

        $this->assertTrue($result);
        $this->assertEquals($newData['title'], $template->fresh()->title);
    }

    public function testDelete()
    {
        $template = CustomerContractTemplate::factory()->create();

        $result = $this->service->delete($template);

        $this->assertTrue($result);
        $this->assertNull(CustomerContractTemplate::find($template->id));
    }

    public function testGetById()
    {
        $template = CustomerContractTemplate::factory()->create();

        $result = $this->service->getById($template->id);

        $this->assertInstanceOf(CustomerContractTemplate::class, $result);
        $this->assertEquals($template->id, $result->id);
    }

    public function testGetAll()
    {
        CustomerContractTemplate::factory()->count(3)->create();

        $results = $this->service->getAll();

        $this->assertCount(3, $results);
        $this->assertInstanceOf(CustomerContractTemplate::class, $results->first());
    }
}
