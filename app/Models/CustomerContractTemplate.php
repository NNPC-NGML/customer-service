<?php

namespace App\Models;

use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'status', 'created_by_user_id'];

    public function contracts()
    {
        return $this->hasMany(CustomerContract::class, 'contract_type_id');
    }
}
