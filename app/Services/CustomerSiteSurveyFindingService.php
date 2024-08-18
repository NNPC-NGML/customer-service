<?php

namespace App\Services;

use App\Models\CustomerSiteSurveyFinding;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerSiteSurveyFindingService
{
    public function createSurveyFinding(array $data): CustomerSiteSurveyFinding
    {
          $rules = [
            'customer_id' => 'required|integer|exists:users,id',
            'customer_site_id' => 'required|integer|exists:customer_sites,id', //TODO::replace model check with actual customer sites model when available
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
