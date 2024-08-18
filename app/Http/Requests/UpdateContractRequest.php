<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Contract Request",
 *      description="Update Contract request body data",
 *      type="object"
 * )
 */

class UpdateContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * @OA\Property(property="customer_id", type="integer", example=1)
     * @OA\Property(property="customer_site_id", type="integer", example=1)
     * @OA\Property(property="contract_type_id", type="integer", example=1)
     * @OA\Property(property="before_erp", type="boolean", example=true)
     * @OA\Property(property="status", type="boolean", example=true)
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
    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes',
            'customer_site_id' => 'sometimes',
            'contract_type_id' => 'sometimes',
            'before_erp' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
        ];
    }
}
