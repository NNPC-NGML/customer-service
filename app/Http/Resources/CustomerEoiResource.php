<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EoiResource",
 *     type="object",
 *     title="Expression of Interest (EOI)",
 *     @OA\Property(property="id", type="integer", example=1, description="ID of the EOI"),
 *     @OA\Property(property="user_id", type="integer", example=123, description="ID of the user associated with the EOI"),
 *     @OA\Property(property="file_path", type="string", example="/path/to/eoi-file.pdf", description="Path to the EOI file"),
 *     @OA\Property(property="customer_id", type="integer", example=456, description="ID of the customer associated with the EOI"),
 *     @OA\Property(property="customer_site_id", type="integer", example=789, description="ID of the customer site associated with the EOI"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected"}, example="pending", description="Status of the EOI"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the EOI"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the EOI")
 * )
 */
class CustomerEoiResource extends JsonResource
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
            'user_id' => $this->user_id,
            'file_path' => $this->file_path,
            'customer_id' => $this->customer_id,
            'customer_site_id' => $this->customer_site_id,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
