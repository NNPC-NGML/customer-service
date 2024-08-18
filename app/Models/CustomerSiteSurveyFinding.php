<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSiteSurveyFinding extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_site_id',
        'file_path',
        'created_by_user_id',
        'status',
    ];
}
