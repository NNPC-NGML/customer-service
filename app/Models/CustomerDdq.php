<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDdq extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'customer_id',
        'customer_site_id',
        'group_id',
        'subgroup_id',
        'document_type',
        'created_by_user_id',
        'status',
    ];
}
