<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSurveyCapex extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_site_id',
        'customer_proposed_daily_consumption',
        'project_cost_in_naira',
        'gas_rate_per_scuf_in_naira',
        'dollar_rate',
        'capex_file_path',
        'created_by_user_id',
        'status',
    ];
}
