<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Contract Request",
 *      description="Store Contract request body data",
 *      type="object",
 *      required={"customer_id", "customer_site_id", "contract_type_id", "before_erp", "status"}
 * )
 */

class StoreContractRequest extends FormRequest
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
     * @OA\Property(property="contract_type_id", type="integer", example=1)
     * @OA\Property(property="before_erp", type="boolean", example=true)
     * @OA\Property(property="status", type="boolean", example=true)
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required',
            'customer_site_id' => 'required',
            'contract_type_id' => 'required',
            'before_erp' => 'required|boolean',
            'status' => 'required|boolean',
        ];
    }
}
