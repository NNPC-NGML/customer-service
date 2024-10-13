<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerSite extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "site_address",
        "ngml_zone_id",
        "site_name",
        "phone_number",
        "email",
        "site_contact_person_name",
        "site_contact_person_email",
        "site_contact_person_phone_number",
        "site_contact_person_signature",
        "site_existing_status",
        "rate",
        "created_by_user_id",
        "status",
    ];

    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
