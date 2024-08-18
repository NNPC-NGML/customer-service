<?php

namespace App\Models;


use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractDetailsOld extends Model
{
    use HasFactory;

    protected $fillable = ['contract_id', 'file_path', 'customer_id', 'customer_site_id', 'created_by_user_id', 'status'];

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }



    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
