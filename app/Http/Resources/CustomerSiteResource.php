<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerSiteResource extends JsonResource
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
            'task_id' => $this->task_id,
            'site_address' => $this->site_address,
            'ngml_zone_id' => $this->ngml_zone_id,
            'site_name' => $this->site_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'site_contact_person_name' => $this->site_contact_person_name,
            'site_contact_person_email' => $this->site_contact_person_email,
            'site_contact_person_phone_number' => $this->site_contact_person_phone_number,
            'site_existing_status' => (bool)$this->site_existing_status,
            'status' => (bool)$this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
