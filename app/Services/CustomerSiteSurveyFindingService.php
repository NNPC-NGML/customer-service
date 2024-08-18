<?php

namespace App\Services;

use App\Models\CustomerSiteSurveyFinding;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerSiteSurveyFindingService
{
     /**
     * Validate and create a new survey finding.
     *
     * @param array $data
     * @return CustomerSiteSurveyFinding
     * @throws ValidationException
     */
    public function createSurveyFinding(array $data): CustomerSiteSurveyFinding
    {
          $rules = [
            'customer_id' => 'required|integer|exists:users,id',
            'customer_site_id' => 'required|integer|exists:customer_sites,id',
            'file_path' => 'required|string|max:255',
            'created_by_user_id' => 'required|integer|exists:users,id',
            'status' => 'required|boolean',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return CustomerSiteSurveyFinding::create($data);
    }
}
