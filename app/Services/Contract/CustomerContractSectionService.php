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

    // public function update(int $id, array $data): CustomerContractSection
    // {
    //     $section = $this->getById($id);
    //     if (!$section) {
    //         throw new \Exception("CustomerContractSection not found");
    //     }
    //     $validatedData = $this->validate($data, $id, true);
    //     $section->update($validatedData);
    //     return $section;
    // }

    // public function delete(int $id): bool
    // {
    //     $section = $this->getById($id);
    //     if (!$section) {
    //         return false;
    //     }
    //     return $section->delete();
    // }

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
            'customer_id' => 'sometimes|required|integer',
            'customer_site_id' => 'sometimes|required|integer',
            'contract_id' => 'sometimes|required|integer',
            'title' => 'sometimes|required|string',
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
