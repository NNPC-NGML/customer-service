<?php

namespace Tests\Unit;

use App\Models\CustomerSite;
use App\Models\CustomerSiteSurveyFinding;
use App\Models\User;
use App\Services\CustomerSiteSurveyFindingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CustomerSiteSurveyFindingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $surveyFindingService;

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $customer;

     /**
     * @var CustomerSite
     */
    private $customerSite;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyFindingService = new CustomerSiteSurveyFindingService();

        $this->user = User::factory()->create();
        $this->customer = User::factory()->create();
        $this->customerSite = CustomerSite::factory()->create();
    }

    /** @test */
    public function it_creates_a_survey_finding_successfully()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'customer_site_id' => $this->customerSite->id,
            'file_path' => 'path/to/file.pdf',
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $surveyFinding = $this->surveyFindingService->createSurveyFinding($data);

        $this->assertInstanceOf(CustomerSiteSurveyFinding::class, $surveyFinding);
        $this->assertDatabaseHas('customer_site_survey_findings', $data);
    }

    /** @test */
    public function it_fails_to_create_a_survey_finding_when_required_data_is_missing()
    {
        $invalidData = [
            // 'customer_id' => $this->customer->id, // This is missing
            'customer_site_id' => $this->customerSite->id,
            'file_path' => 'path/to/file.pdf',
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ];

        $this->expectException(ValidationException::class);

        $this->surveyFindingService->createSurveyFinding($invalidData);

        $this->assertDatabaseMissing('customer_site_survey_findings', $invalidData);
    }
}
