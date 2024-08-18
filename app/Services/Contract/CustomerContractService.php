<?php

namespace App\Services\Contract;

use App\Models\CustomerContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerContractService
{
    public function create(array $data): CustomerContract
    {
        $validatedData = $this->validate($data);
        return CustomerContract::create($validatedData);
    }

    public function update(int $id, array $data): CustomerContract
    {
        $contract = $this->getById($id);
        if (!$contract) {
            throw new \Exception("CustomerContract not found");
        }
        $validatedData = $this->validate($data, $id, true);
        $contract->update($validatedData);
        return $contract;
    }

    public function delete(int $id): bool
    {
        $contract = $this->getById($id);
        if (!$contract) {
            return false;
        }
        return $contract->delete();
    }

    public function getById(int $id): ?CustomerContract
    {
        return CustomerContract::findOrFail($id)->load(['contractType', 'sections', 'detailsNews', 'detailsOlds', 'parentAddendums', 'childAddendums', 'signatures']);
    }

    public function getAll()
    {
        return CustomerContract::all();
    }

    private function validate(array $data, $id = null, $partial = false): array
    {
        $rules = [
            'customer_id' => 'sometimes|required|integer',
            'customer_site_id' => 'sometimes|required|integer',
            'contract_type_id' => 'sometimes|required|integer',
            'created_by_user_id' => 'sometimes|required|integer|exists:users,id',
            'before_erp' => 'sometimes|required|boolean',
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
