<?php

namespace App\Services\Contract;

use App\Models\CustomerContractSignature;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContractSignatureService
{
    /**
     * Validate and create a new contract signature.
     *
     * @param array $data
     * @return CustomerContractSignature
     * @throws ValidationException
     */
    public function createSignature(array $data): CustomerContractSignature
    {
        $validatedData = $this->validate($data);

        $signature = CustomerContractSignature::create($validatedData + [
            'created_by_user_id' => auth()->id(),
            'status' => true,
        ]);

        return $signature;
    }

    /**
     * Retrieve a contract signature by ID.
     *
     * @param int $id
     * @return CustomerContractSignature|null
     */
    public function getSignatureById(int $id): ?CustomerContractSignature
    {
        return CustomerContractSignature::findOrFail($id);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    protected function validate(array $data): array
    {
        $rules = [

            'contract_id' => 'required|exists:customer_contracts,id',
            'customer_id' => 'required',
            'customer_site_id' => 'required',
            'signature' => 'required|string',
            'title' => 'required|string',
            'signature_type' => 'required|in:user_id,customer_id,file_path',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
