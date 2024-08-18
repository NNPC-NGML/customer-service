<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Addendum Request",
 *      description="Update Addendum request body data",
 *      type="object"
 * )
 */
class UpdateAddendumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     * @OA\Property(property="customer_id", type="integer", example=1)
     * @OA\Property(property="customer_site_id", type="integer", example=1)
     * @OA\Property(property="parent_contract_id", type="integer", example=1)
     * @OA\Property(property="child_contract_id", type="integer", example=2)
     * @OA\Property(property="status", type="boolean", example=true)
     */
    public function rules()
    {
        return [
            'customer_id' => 'sometimes',
            'customer_site_id' => 'sometimes',
            'parent_contract_id' => 'sometimes|exists:customer_contracts,id',
            'child_contract_id' => 'sometimes|exists:customer_contracts,id|different:parent_contract_id',
            'status' => 'sometimes|boolean',
        ];
    }
}
