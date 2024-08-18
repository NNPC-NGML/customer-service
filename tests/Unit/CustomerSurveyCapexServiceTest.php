<?php

namespace Tests\Unit;

use App\Models\CustomerSite;
use App\Models\CustomerSurveyCapex;
use App\Models\User;
use App\Services\CustomerSurveyCapexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CustomerSurveyCapexServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $capexService;
    protected $user;
    protected $customerSite;

    protected function setUp(): void
    {
        parent::setUp();
        $this->capexService = new CustomerSurveyCapexService();

        // Create a user and customer site for testing
        $this->user = User::factory()->create();
        $this->customerSite = CustomerSite::factory()->create([
            'customer_id' => $this->user->id,
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
}
