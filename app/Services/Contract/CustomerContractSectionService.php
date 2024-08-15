<?php

namespace App\Services\Contract;

use App\Models\CustomerContractSection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerContractSectionService
{
    public function create(array $data): CustomerContractSection
    {
        $validatedData = $this->validate($data);
        return CustomerContractSection::create($validatedData);
    }

    public function update(CustomerContractSection $section, array $data): bool
    {
        $validatedData = $this->validate($data, $section->id, true);
        return $section->update($validatedData);
    }

    public function delete(CustomerContractSection $section): bool
    {
        return $section->delete();
    }

    public function getById(int $id): ?CustomerContractSection
    {
        return CustomerContractSection::find($id);
    }

    public function getAll()
    {
        return CustomerContractSection::all();
    }

    private function validate(array $data, $id = null, $partial = false): array
    {
        $rules = [
            'contract_id' => 'sometimes|required|integer',
            'section_id' => 'sometimes|required|integer',
            'details' => 'sometimes|required|string',
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
