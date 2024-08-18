<?php

namespace App\Models;

use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractDetailsNew extends Model
{
    use HasFactory;

    protected $fillable = ['contract_id', 'section_id', 'details', 'customer_id', 'customer_site_id', 'created_by_user_id', 'status'];

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }

    public function section()
    {
        return $this->belongsTo(CustomerContractSection::class, 'section_id');
    }

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }


    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
