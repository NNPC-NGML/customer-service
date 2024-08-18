<?php

namespace App\Services\Contract;

use App\Models\CustomerContractType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerContractTypeService
{
    public function create(array $data): CustomerContractType
    {
        $validatedData = $this->validate($data);
        return CustomerContractType::create($validatedData);
    }


    public function getById(int $id): ?CustomerContractType
    {
        return CustomerContractType::find($id);
    }
    public function getAll()
    {
        return CustomerContractType::all();
    }

    private function validate(array $data, $id = null, $partial = false): array
    {
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|integer|in:0,1',
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
