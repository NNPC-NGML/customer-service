<?php

namespace App\Services;

use App\Models\CustomerCapexApproval;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CustomerCapexApprovalService
{
    /**
     * Create a new Customer Capex Approval.
     *
     * @param array $data
     * @return CustomerCapexApproval
     * @throws ValidationException
     */
    public function createApproval(array $data): CustomerCapexApproval
    {
        $rules =  [
            'customer_id' => 'required|integer|exists:users,id',
            'customer_site_id' => 'required|integer|exists:customer_sites,id',
            'approval_type_id' => 'required|integer|exists:customer_approval_types,id',
            'approved_by_user_id' => 'required|integer|exists:users,id',
            'comment' => 'required|string|max:255',
            'status' => 'boolean',
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return CustomerCapexApproval::create($data);
    }
}
