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
            'status' => 'boolean',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return CustomerSiteSurveyFinding::create($data);
    }

      /**
     * Validate and update an existing survey finding.
     *
     * @param int $id
     * @param array $data
     * @return CustomerSiteSurveyFinding
     * @throws ValidationException
     */
    public function updateSurveyFinding(int $id, array $data): CustomerSiteSurveyFinding
    {
        $rules = [
            'customer_id' => 'sometimes|integer|exists:customers,id',
            'customer_site_id' => 'sometimes|integer|exists:customer_sites,id',
            'file_path' => 'sometimes|string|max:255',
            'created_by_user_id' => 'sometimes|integer|exists:users,id',
            'status' => 'boolean',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $surveyFinding = CustomerSiteSurveyFinding::findOrFail($id);
        $surveyFinding->update($data);

        return $surveyFinding;
    }
}
