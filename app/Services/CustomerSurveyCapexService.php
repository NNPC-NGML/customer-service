<?php

namespace App\Services;

use App\Models\CustomerSurveyCapex;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CustomerSurveyCapexService
{
    /**
     * Create a new Customer Survey Capex.
     *
     * @param array $data
     * @return CustomerSurveyCapex
     * @throws ValidationException
     */
    public function createCapex(array $data): CustomerSurveyCapex
    {
        $this->validate($data);

        return CustomerSurveyCapex::create($data);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data
     * @throws ValidationException
     */
    protected function validate(array $data)
    {
        $validator = Validator::make($data, [
            'customer_id' => 'required|integer|exists:users,id',
            'customer_site_id' => 'required|integer|exists:customer_sites,id',
            'customer_proposed_daily_consumption' => 'required|string|max:255',
            'project_cost_in_naira' => 'required|string|max:255',
            'gas_rate_per_scuf_in_naira' => 'required|string|max:255',
            'dollar_rate' => 'required|string|max:255',
            'capex_file_path' => 'required|string|max:255',
            'created_by_user_id' => 'required|integer|exists:users,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
