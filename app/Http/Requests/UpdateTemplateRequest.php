<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Template Request",
 *      description="Update Template request body data",
 *      type="object"
 * )
 */

class UpdateTemplateRequest extends FormRequest
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
     * @OA\Property(property="title", type="string", example="Updated Contract Template")
     * @OA\Property(property="status", type="boolean", example=true)
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
        ];
    }
}
