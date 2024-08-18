<?php

namespace App\Services\Contract;

use App\Models\CustomerContractAddendum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerContractAddendumService
{
    public function attachAddendum(array $data): CustomerContractAddendum
    {
        $validatedData = $this->validate($data);

        return CustomerContractAddendum::create($validatedData);
    }


    public function getAddendumById(int $id): ?CustomerContractAddendum
    {
        return CustomerContractAddendum::with(['parentContract', 'childContract'])->find($id);
    }

    public function getAllAddendums()
    {
        return CustomerContractAddendum::with(['parentContract', 'childContract'])->get();
    }

    private function validate(array $data, int $id = null, bool $partial = false): array
    {
        $rules = [
            'customer_id' => 'required|integer|exists:users,id',
            'customer_site_id' => 'required|integer|exists:users,id',
            'parent_contract_id' => 'required|integer|exists:customer_contracts,id',
            'child_contract_id' => 'sometimes|required|integer|exists:customer_contracts,id',
            'created_by_user_id' => 'required|integer|exists:users,id',
            'status' => 'sometimes|required|boolean',
        ];

        if ($partial) {
            // Modify rules to be optional for partial updates
            $rules = array_map(function ($rule) {
                return 'sometimes|' . $rule;
            }, $rules);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
