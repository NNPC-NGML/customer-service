<?php

namespace App\Services\Contract;


use Illuminate\Support\Facades\Validator;
use App\Models\CustomerContractDetailsNew;
use Illuminate\Validation\ValidationException;

class CustomerContractDetailsNewService
{
    public function create(array $data): CustomerContractDetailsNew
    {
        $validatedData = $this->validate($data);
        return CustomerContractDetailsNew::create($validatedData);
    }

    public function getById(int $id): ?CustomerContractDetailsNew
    {
        return CustomerContractDetailsNew::find($id);
    }

    public function getAll()
    {
        return CustomerContractDetailsNew::all();
    }

    private function validate(array $data, $id = null, $partial = false): array
    {

        $rules = [
            'contract_id' => 'sometimes|required|integer',
            'details' => 'sometimes|required|string',
            'section_id' => 'sometimes|required|integer',
            'customer_id' => 'sometimes|required|integer',
            'customer_site_id' => 'sometimes|required|integer',
            'created_by_user_id' => 'sometimes|required|integer|exists:users,id',
            'status' => 'sometimes|required|boolean',
        ];

        if (!$partial) {
            $rules = array_map(function ($rule) {
                return str_replace('sometimes|', '', $rule);
            }, $rules);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
