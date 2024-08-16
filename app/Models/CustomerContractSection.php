<?php

namespace App\Models;

use App\Models\User;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerContractDetailsNew;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractSection extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'customer_site_id', 'contract_id', 'title_id', 'created_by_user_id', 'status'];

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }


    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function detailsNews()
    {
        return $this->hasMany(CustomerContractDetailsNew::class, 'section_id');
    }
}
