<?php

namespace App\Services;

use App\Models\CustomerSite;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CustomerSiteService
{
    public function create(array $data): CustomerSite
    {
        $validator = Validator::make($data, [
            'customer_id' => 'required|integer|exists:customers,id',
            'site_address' => 'required|string|max:255',
            'ngml_zone_id' => 'required|integer',
            'site_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'site_contact_person_name' => 'required|string|max:255',
            'site_contact_person_email' => 'required|email|max:255',
            'site_contact_person_phone_number' => 'required|string|max:20',
            'site_contact_person_signature' => 'nullable|string',
            'site_existing_status' => 'boolean',
            'created_by_user_id' => 'required|integer|exists:users,id',
            'status' => 'boolean',
        ]);

        $validator->validate();

        return CustomerSite::create($data);
    }
}
