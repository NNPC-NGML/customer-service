<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDdqExisting extends Model
{
    use HasFactory;

    protected $table = 'customer_ddq_existings';

    protected $fillable = [
        'user_id',
        'file_path',
        'customer_id',
        'customer_site_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerSite()
    {
        return $this->belongsTo(CustomerSite::class);
    }
}
