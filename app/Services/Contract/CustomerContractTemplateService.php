<?php

namespace App\Services\Contract;

use App\Models\CustomerContractTemplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerContractTemplateService
{
    public function create(array $data): CustomerContractTemplate
    {
        $validatedData = $this->validate($data);
        return CustomerContractTemplate::create($validatedData);
    }

    public function update(CustomerContractTemplate $template, array $data): bool
    {
        $validatedData = $this->validate($data, $template->id);
        return $template->update($validatedData);
    }

    public function delete(CustomerContractTemplate $template): bool
    {
        return $template->delete();
    }

    public function getById(int $id): ?CustomerContractTemplate
    {
        return CustomerContractTemplate::find($id);
    }

    public function getAll()
    {
        return CustomerContractTemplate::all();
    }

    private function validate(array $data, $id = null, $partial = false): array
    {
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'created_by_user_id' => 'sometimes|required|integer|exists:users,id',
            'status' => 'sometimes|required|boolean',
        ];

        if ($id) {
            $rules['title'] .= '|unique:customer_contract_templates,title,' . $id;
        } else {
            $rules['title'] .= '|unique:customer_contract_templates,title';
        }

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
