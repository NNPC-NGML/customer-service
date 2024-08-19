<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
