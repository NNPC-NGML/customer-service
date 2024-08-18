<?php

namespace Tests\Unit\Services\Contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractType;
use App\Models\CustomerContractSection;
use App\Models\CustomerContractDetailsNew;
use App\Models\CustomerContractDetailsOld;
use App\Services\Contract\ContractService;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Contract\CustomerContractService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractTypeService;
use App\Services\Contract\CustomerContractSectionService;
use App\Services\Contract\CustomerContractDetailsNewService;
use App\Services\Contract\CustomerContractDetailsOldService;

class ContractServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private ContractService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ContractService(
            new CustomerContractService(),
            new CustomerContractTypeService(),
            new CustomerContractDetailsNewService(),
            new CustomerContractDetailsOldService(),
            new CustomerContractSectionService()
        );
    }

    public function testCreateContractWithExistingContractType()
    {
        $user = User::factory()->create();
        $contractType = CustomerContractType::create([
            'title' => 'Existing Type',
            'status' => true
        ]);

        $data = [
            'contract_type_id' => $contractType->id,
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'before_erp' => false,
            'sections' => [
                ['title' => 'SECTION1']
            ]
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContract::class, $result);
        $this->assertEquals($contractType->id, $result->contract_type_id);
        $this->assertFalse($result->before_erp);
    }

    public function testCreateContractWithNewContractType()
    {
        $user = User::factory()->create();

        $data = [
            'contract_type_title' => 'New Type',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'before_erp' => true,
            'file_path' => '/path/to/file.pdf'
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContract::class, $result);
        $this->assertTrue($result->before_erp);

        $this->assertDatabaseHas('customer_contract_types', [
            'title' => 'New Type'
        ]);

        $this->assertDatabaseHas('customer_contract_details_olds', [
            'contract_id' => $result->id,
            'file_path' => '/path/to/file.pdf'
        ]);
    }

    public function testCreateContractThrowsExceptionOnFailure()
    {
        $this->expectException(\Exception::class);

        $data = [
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => 999 // Non-existent user ID
        ];

        $this->service->create($data);
    }

    public function testUpdate()
    {


        $user = User::factory()->create();
        $contractType = CustomerContractType::create([
            'title' => 'Existing Type',
            'status' => true
        ]);

        $data = [
            'contract_type_id' => $contractType->id,
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'before_erp' => false,
            'sections' => [
                ['title' => 'SECTION1']
            ]
        ];

        $result = $this->service->create($data);
        $result->customer_id = 5;
        $result->customer_site_id = 5;
        $result->sections[0]->title = 'New Section 2 updated';


        $updatedContract = $this->service->update($result->id, $result->toArray());

        $this->assertInstanceOf(CustomerContract::class, $updatedContract);


        // $this->assertEquals('New Section 2 updated', $updatedContract->sections[0]->title);
    }

    public function testViewContract()
    {

        $user = User::factory()->create();
        $contractType = CustomerContractType::create([
            'title' => 'Existing Type',
            'status' => true
        ]);


        $contract = CustomerContract::create([
            'customer_id' => 1,
            'customer_site_id' => 1,
            'contract_type_id' => $contractType->id,
            'created_by_user_id' => $user->id,
            'before_erp' => false,
            'status' => true,
        ]);
        $section = CustomerContractSection::create([
            'contract_id' => $contract->id,
            'title' => 'SECTION1',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'status' => true,
        ]);

        $detailsNew = CustomerContractDetailsNew::create([
            'contract_id' => $contract->id,
            'details' => 'Details about the contract',
            'section_id' => $section->id,
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'status' => true,
        ]);

        $detailsOld = CustomerContractDetailsOld::create([
            'contract_id' => $contract->id,
            'file_path' => 'path/to/file',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'status' => true,
        ]);

        // Call the view method
        $retrievedContract = $this->service->view($contract->id);

        // Assertions
        $this->assertInstanceOf(CustomerContract::class, $retrievedContract);
        $this->assertEquals($contract->id, $retrievedContract->id);
        $this->assertNotNull($retrievedContract->contractType);
        $this->assertCount(1, $retrievedContract->sections);
        $this->assertCount(1, $retrievedContract->detailsNews);
        $this->assertCount(1, $retrievedContract->detailsOlds);
        $this->assertEquals('SECTION1', $retrievedContract->sections->first()->title);
        $this->assertEquals('Details about the contract', $retrievedContract->detailsNews->first()->details);
        $this->assertEquals('path/to/file', $retrievedContract->detailsOlds->first()->file_path);
    }


    public function testRemoveContract()
    {
        $user = User::factory()->create();
        $contractType = CustomerContractType::create([
            'title' => 'Existing Type',
            'status' => true
        ]);

        $data = [
            'contract_type_id' => $contractType->id,
            'customer_id' => 1,
            'customer_site_id' => 1,
            'created_by_user_id' => $user->id,
            'before_erp' => false,
            'sections' => [
                ['title' => 'SECTION1']
            ]
        ];

        $result = $this->service->create($data);

        $this->assertInstanceOf(CustomerContract::class, $result);
        $this->assertEquals($contractType->id, $result->contract_type_id);

        $deleted = $this->service->remove($result->id);
        $this->assertTrue($deleted);
    }
}
