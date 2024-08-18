<?php

namespace App\Services;

use App\Models\CustomerSurveyCapex;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * Update an existing Customer Survey Capex.
     *
     * @param int $id
     * @param array $data
     * @return CustomerSurveyCapex
     * @throws ValidationException
     */
    public function updateCapex(int $id, array $data): CustomerSurveyCapex
    {
        $rules = [
            'customer_id' => 'sometimes|integer|exists:users,id',
            'customer_site_id' => 'sometimes|integer|exists:customer_sites,id',
            'customer_proposed_daily_consumption' => 'sometimes|string|max:255',
            'project_cost_in_naira' => 'sometimes|string|max:255',
            'gas_rate_per_scuf_in_naira' => 'sometimes|string|max:255',
            'dollar_rate' => 'sometimes|string|max:255',
            'capex_file_path' => 'sometimes|string|max:255',
            'created_by_user_id' => 'sometimes|integer|exists:users,id',
            'status' => 'sometimes|boolean',
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $capex = CustomerSurveyCapex::findOrFail($id);
        $capex->update($data);

        return $capex;
    }

    /**
     * Delete a Customer Survey Capex by its ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteCapex(int $id): bool
    {
        $capex = CustomerSurveyCapex::findOrFail($id);
        return $capex->delete();
    }
}
