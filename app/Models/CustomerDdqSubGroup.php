<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDdqSubGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'customer_ddq_group_id',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
