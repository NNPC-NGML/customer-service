<?php

namespace Tests\Unit;

use App\Models\CustomerSite;
use App\Models\CustomerSurveyCapex;
use App\Models\User;
use App\Services\CustomerSurveyCapexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class CustomerSurveyCapexServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $capexService;
    protected $user;
    protected $customerSite;
    protected $capex;

    protected function setUp(): void
    {
        parent::setUp();
        $this->capexService = new CustomerSurveyCapexService();

        // Create a user and customer site for testing
        $this->user = User::factory()->create();
        $this->customerSite = CustomerSite::factory()->create([
            'customer_id' => $this->user->id,
        ]);
        $this->capex = CustomerSurveyCapex::factory()->create([
            'customer_id' => $this->user->id,
            'customer_site_id' => $this->customerSite->id,
        ]);
    }

    /** @test */
    public function it_creates_a_new_customer_survey_capex()
    {
        $data = [
            'customer_id' => $this->user->id,
            'customer_site_id' => $this->customerSite->id,
            'customer_proposed_daily_consumption' => '1000 SCUF',
            'project_cost_in_naira' => '15000000',
            'gas_rate_per_scuf_in_naira' => '200',
            'dollar_rate' => '500',
            'capex_file_path' => 'path/to/capex/file.pdf',
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $capex = $this->capexService->createCapex($data);

        $this->assertInstanceOf(CustomerSurveyCapex::class, $capex);
        $this->assertDatabaseHas('customer_survey_capexes', $data);
    }

    /** @test */
    public function it_fails_to_create_capex_with_invalid_data()
    {
        $invalidData = [
            'customer_id' => $this->user->id,
            // 'customer_site_id' is missing
            'customer_proposed_daily_consumption' => '1000 SCUF',
            'project_cost_in_naira' => '15000000',
            'gas_rate_per_scuf_in_naira' => '200',
            'dollar_rate' => '500',
            'capex_file_path' => 'path/to/capex/file.pdf',
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $this->expectException(ValidationException::class);

        $this->capexService->createCapex($invalidData);

        $this->assertDatabaseMissing('customer_survey_capexes', $invalidData);
    }


    /** @test */
    public function it_updates_an_existing_customer_survey_capex()
    {

        $projectCost = '20000000';
        $dollarRate = '550';

        $updateData = [
            'project_cost_in_naira' => $projectCost,
            'dollar_rate' => $dollarRate,
        ];

        $updatedCapex = $this->capexService->updateCapex($this->capex->id, $updateData);

        $this->assertInstanceOf(CustomerSurveyCapex::class, $updatedCapex);
        $this->assertEquals($projectCost, $updatedCapex->project_cost_in_naira);
        $this->assertEquals($dollarRate, $updatedCapex->dollar_rate);
    }

    /** @test */
    public function it_fails_to_update_capex_with_invalid_data()
    {
        $invalidUpdateData = [
            'customer_id' => 'invalid', // Invalid customer_id
        ];

        $this->expectException(ValidationException::class);

        $this->capexService->updateCapex($this->capex->id, $invalidUpdateData);

        $this->assertDatabaseMissing('customer_survey_capexes', $invalidUpdateData);
    }

    /** @test */
    public function it_deletes_a_customer_survey_capex_successfully()
    {
        $deleted = $this->capexService->deleteCapex($this->capex->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('customer_survey_capexes', ['id' => $this->capex->id]);
    }

    /** @test */
    public function it_throws_model_not_found_exception_if_capex_does_not_exist()
    {
        $nonExistentId = mt_rand(1000000000, 9999999999);

        $this->expectException(ModelNotFoundException::class);

        $this->capexService->deleteCapex($nonExistentId);
    }
}
