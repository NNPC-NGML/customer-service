<?php

namespace App\Models;

use App\Models\User;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractSignature extends Model
{
    use HasFactory;


    protected $fillable = ['contract_id', 'customer_id', 'customer_site_id', 'signature', 'title', 'created_by_user_id', 'signature_type', 'status'];

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }


    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
