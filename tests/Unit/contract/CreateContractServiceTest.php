<?php

namespace Tests\Unit\Services\Contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractTemplate;
use App\Services\Contract\CreateContractService;
use App\Services\Contract\CustomerContractService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractTypeService;
use App\Services\Contract\CustomerContractTemplateService;
use App\Services\Contract\CustomerContractDetailsNewService;
use App\Services\Contract\CustomerContractDetailsOldService;

/**
 * Unit tests for the CreateContractService class.
 *
 * This class contains tests for creating contracts and contract templates,
 * ensuring that the relevant database tables are updated correctly.
 */
class CreateContractServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CreateContractService
     */
    private $createContract;

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $customer;

    /**
     * Set up the test environment.
     *
     * This method is called before each test method is executed. It initializes the
     * CreateContractService with the required dependencies and creates a test user
     * and customer in the database.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createContract = new CreateContractService(
            new CustomerContractService(),
            new CustomerContractTypeService(),
            new CustomerContractDetailsNewService(),
            new CustomerContractDetailsOldService(),
            new CustomerContractTemplateService(),
            // new CustomerContractSectionService()
        );

        $this->user = User::factory()->create();
        $this->customer = User::factory()->create();
    }

    /**
     * Test creating a contract template with new details.
     *
     * This test verifies that a contract template is correctly created
     * with the specified data, including customer, contract type, and template.
     * It also checks that the corresponding records are present in the database.
     *
     * @return void
     */
    public function testCreateContractTemplate()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'customer_site_id' => $this->customer->id,
            'contract_type_id' => null,
            'contract_type_title' => 'Test Contract Type',
            'template_title' => 'Test Template',
            'created_by_user_id' => $this->user->id,
            'before_erp' => false,
            'status' => true,
            'sections' => [
                ['id' => 1, 'details' => 'Section 1 details'],
                ['id' => 2, 'details' => 'Section 2 details'],
            ],
        ];

        $result = $this->createContract->createContractTemplate($data);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(CustomerContract::class, $result[0]);
        $this->assertInstanceOf(CustomerContractTemplate::class, $result[1]);

        $this->assertDatabaseHas('customer_contracts', [
            'customer_id' => $this->customer->id,
            'customer_site_id' => $this->customer->id,
            'created_by_user_id' => $this->user->id,
            'before_erp' => false,
            'status' => true,
        ]);

        $this->assertDatabaseHas('customer_contract_types', [
            'title' => 'Test Contract Type',
            'status' => 1,
        ]);

        $this->assertDatabaseHas('customer_contract_templates', [
            'title' => 'Test Template',
            'status' => 1,
        ]);

        $this->assertDatabaseCount('customer_contract_details_news', 2);
    }

    /**
     * Test creating a contract template with old details.
     *
     * This test verifies that a contract template is correctly created
     * with the specified data, including customer, contract type, and template.
     * It also ensures that records are present in the database, especially for
     * old contract details.
     *
     * @return void
     */
    public function testCreateContractTemplateWithOldDetails()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'customer_site_id' => $this->customer->id,
            'contract_type_id' => null,
            'contract_type_title' => 'Test Contract Type',
            'template_title' => 'Test Template',
            'created_by_user_id' => $this->user->id,
            'before_erp' => true,
            'status' => true,
            'file_path' => '/path/to/file.pdf',
            'sections' => [
                ['id' => 1, 'details' => 'Section 1 details'],
                ['id' => 2, 'details' => 'Section 2 details'],
            ],
        ];

        $result = $this->createContract->createContractTemplate($data);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(CustomerContract::class, $result[0]);
        $this->assertInstanceOf(CustomerContractTemplate::class, $result[1]);

        $this->assertDatabaseHas('customer_contracts', [
            'customer_id' => $this->customer->id,
            'customer_site_id' => $this->customer->id,
            'created_by_user_id' => $this->user->id,
            'before_erp' => true,
            'status' => true,
        ]);

        $this->assertDatabaseHas('customer_contract_types', [
            'title' => 'Test Contract Type',
            'status' => 1,
        ]);

        $this->assertDatabaseHas('customer_contract_templates', [
            'title' => 'Test Template',
            'status' => 1,
        ]);

        $this->assertDatabaseCount('customer_contract_details_news', 2);

        $this->assertDatabaseHas('customer_contract_details_olds', [
            'file_path' => '/path/to/file.pdf',
            'customer_id' => $this->customer->id,
            'customer_site_id' => $this->customer->id,
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ]);
    }
}
