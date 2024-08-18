<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Sign Contract Request",
 *      description="Sign Contract request body data",
 *      type="object",
 *      required={"contract_id", "signature", "title", "signature_type"}
 * )
 */
class SignContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * @OA\Property(property="contract_id", type="integer", example=1)
     * @OA\Property(property="customer_id", type="integer", example=1)
     * @OA\Property(property="customer_site_id", type="integer", example=1)
     * @OA\Property(property="signature", type="string", example="John Doe")
     * @OA\Property(property="title", type="string", example="CEO")
     * @OA\Property(property="signature_type", type="string", enum={"user_id", "customer_id", "file_path"}, example="user_id")
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'contract_id' => 'required|exists:customer_contracts,id',
            'customer_id' => 'required',
            'customer_site_id' => 'required',
            'signature' => 'required|string',
            'title' => 'required|string',
            'signature_type' => 'required|in:user_id,customer_id,file_path',

        ];
    }
}
