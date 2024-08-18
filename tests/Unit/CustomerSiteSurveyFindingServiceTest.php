<?php

namespace Tests\Unit;

use App\Models\CustomerSite;
use App\Models\CustomerSiteSurveyFinding;
use App\Models\User;
use App\Services\CustomerSiteSurveyFindingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    private $surveyFinding;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyFindingService = new CustomerSiteSurveyFindingService();

        $this->user = User::factory()->create();
        $this->customer = User::factory()->create();
        $this->customerSite = CustomerSite::factory()->create();
        $this->surveyFinding = CustomerSiteSurveyFinding::factory()->create();
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


    /** @test */
    public function it_updates_a_survey_finding_successfully()
    {
        $updateData = [
            'file_path' => 'updated/path/to/file.pdf',
        ];

        $updatedSurveyFinding = $this->surveyFindingService->updateSurveyFinding($this->surveyFinding->id, $updateData);

        $this->assertInstanceOf(CustomerSiteSurveyFinding::class, $updatedSurveyFinding);
        // Exclude the timestamps from the comparison
        $expectedData = array_merge(
            $this->surveyFinding->only(['id', 'customer_id', 'customer_site_id', 'created_by_user_id', 'status']),
            $updateData
        );

        $this->assertDatabaseHas('customer_site_survey_findings', $expectedData);
    }


    /** @test */
    public function it_fails_to_update_a_survey_finding_with_invalid_data()
    {
        $invalidUpdateData = [
            'customer_id' => 'invalid', // Invalid customer_id
        ];

        $this->expectException(ValidationException::class);

        $this->surveyFindingService->updateSurveyFinding($this->surveyFinding->id, $invalidUpdateData);

        $this->assertDatabaseMissing('customer_site_survey_findings', $invalidUpdateData);
    }

        /** @test */
        public function it_deletes_a_survey_finding_successfully()
        {
            $deleted = $this->surveyFindingService->deleteSurveyFinding($this->surveyFinding->id);

            $this->assertTrue($deleted);
            $this->assertDatabaseMissing('customer_site_survey_findings', ['id' => $this->surveyFinding->id]);
        }

        /** @test */
        public function it_throws_model_not_found_exception_if_survey_finding_does_not_exist()
        {
            $nonExistentId = 999;

            $this->expectException(ModelNotFoundException::class);

            $this->surveyFindingService->deleteSurveyFinding($nonExistentId);
        }
}
