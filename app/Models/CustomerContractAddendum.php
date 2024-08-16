<?php

namespace App\Models;

use App\Models\User;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractAddendum extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'customer_site_id', 'parent_contract_id', 'child_contract_id', 'created_by_user_id', 'status'];

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }


    public function parentContract()
    {
        return $this->belongsTo(CustomerContract::class, 'parent_contract_id');
    }

    public function childContract()
    {
        return $this->belongsTo(CustomerContract::class, 'child_contract_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
