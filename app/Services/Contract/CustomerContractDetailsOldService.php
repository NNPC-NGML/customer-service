<?php

namespace App\Services\Contract;

use Illuminate\Support\Facades\Validator;
use App\Models\CustomerContractDetailsOld;
use Illuminate\Validation\ValidationException;

class CustomerContractDetailsOldService
{
    public function create(array $data): CustomerContractDetailsOld
    {
        $validatedData = $this->validate($data);
        return CustomerContractDetailsOld::create($validatedData);
    }

    public function update(CustomerContractDetailsOld $detail, array $data): bool
    {
        $validatedData = $this->validate($data, $detail->id, true);
        return $detail->update($validatedData);
    }

    public function delete(CustomerContractDetailsOld $detail): bool
    {
        return $detail->delete();
    }

    public function getById(int $id): ?CustomerContractDetailsOld
    {
        return CustomerContractDetailsOld::find($id);
    }

    public function getAll()
    {
        return CustomerContractDetailsOld::all();
    }

    public function deleteByContractId(int $contractId): bool
    {
        return CustomerContractDetailsOld::where('contract_id', $contractId)->delete();
    }

    private function validate(array $data, $id = null, $partial = false): array
    {
        $rules = [
            'contract_id' => 'sometimes|required|integer',
            'file_path' => 'sometimes|required',
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
