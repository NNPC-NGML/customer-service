<?php

namespace App\Services;

use App\Models\CustomerSiteSurveyFinding;

class CustomerSiteSurveyFindingService
{
    public function createSurveyFinding(array $data): CustomerSiteSurveyFinding
    {
        return CustomerSiteSurveyFinding::create($data);
    }
}
