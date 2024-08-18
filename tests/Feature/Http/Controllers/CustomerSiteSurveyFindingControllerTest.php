<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\CustomerSiteSurveyFinding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerSiteSurveyFindingControllerTest extends TestCase
{
    use RefreshDatabase;

    private $surveyFinding;
    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyFinding = CustomerSiteSurveyFinding::factory()->create();
    }

     /** @test */
     public function it_returns_a_single_survey_finding_by_id()
     {

         // Use the named route for show, passing the ID
         $response = $this->getJson(route('survey-findings.show', ['id' => $this->surveyFinding->id]));

         $response->assertStatus(200)
                  ->assertJsonFragment([
                      'id' => $this->surveyFinding->id,
                      'file_path' => $this->surveyFinding->file_path,
                  ]);
     }

     /** @test */
     public function it_returns_404_if_survey_finding_does_not_exist()
     {
        $nonExistentId = mt_rand(1000000000, 9999999999);

         $response = $this->getJson(route('survey-findings.show', ['id' => $nonExistentId]));

         $response->assertStatus(404);
     }
}
