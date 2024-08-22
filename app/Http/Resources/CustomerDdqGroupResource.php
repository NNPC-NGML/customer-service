<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CustomerDdqGroup",
 *     type="object",
 *     title="Customer DDQ Group",
 *     @OA\Property(property="id", type="integer", example=1, description="ID of the DDQ Group"),
 *     @OA\Property(property="title", type="string", example="Group Title", description="Title of the DDQ Group"),
 *     @OA\Property(property="status", type="boolean", example=true, description="Status of the DDQ Group"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the DDQ Group"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the DDQ Group")
 * )
 */
class CustomerDdqGroupResource extends JsonResource
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
            'title' => $this->title,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
