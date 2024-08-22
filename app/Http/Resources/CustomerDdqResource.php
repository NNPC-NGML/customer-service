<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CustomerDdq",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="data", type="string", example="Sample data"),
 *     @OA\Property(property="customer_id", type="integer", example=101),
 *     @OA\Property(property="customer_site_id", type="integer", example=202),
 *     @OA\Property(property="group_id", type="integer", example=303),
 *     @OA\Property(property="subgroup_id", type="integer", example=404),
 *     @OA\Property(
 *         property="document_type",
 *         type="string",
 *         enum={"string", "file"},
 *         example="string"
 *     ),
 *     @OA\Property(property="created_by_user_id", type="integer", example=505),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class CustomerDdqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'customer_id' => $this->customer_id,
            'customer_site_id' => $this->customer_site_id,
            'group_id' => $this->group_id,
            'subgroup_id' => $this->subgroup_id,
            'document_type' => $this->document_type,
            'created_by_user_id' => $this->created_by_user_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
