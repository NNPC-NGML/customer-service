<?php

namespace App\Models;

use App\Models\User;

use App\Models\CustomerContractType;
use App\Models\CustomerContractSection;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerContractAddendum;
use App\Models\CustomerContractSignature;
use App\Models\CustomerContractDetailsNew;
use App\Models\CustomerContractDetailsOld;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContract extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'customer_site_id', 'contract_type_id', 'created_by_user_id', 'before_erp', 'status'];

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }


    public function contractType()
    {
        return $this->belongsTo(CustomerContractType::class, 'contract_type_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function sections()
    {
        return $this->hasMany(CustomerContractSection::class, 'contract_id');
    }

    public function detailsNews()
    {
        return $this->hasMany(CustomerContractDetailsNew::class, 'contract_id');
    }

    public function detailsOlds()
    {
        return $this->hasMany(CustomerContractDetailsOld::class, 'contract_id');
    }

    public function signatures()
    {
        return $this->hasMany(CustomerContractSignature::class, 'contract_id');
    }

    public function parentAddendums()
    {
        return $this->hasMany(CustomerContractAddendum::class, 'parent_contract_id');
    }

    public function childAddendums()
    {
        return $this->hasMany(CustomerContractAddendum::class, 'child_contract_id');
    }
}
