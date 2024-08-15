<?php

namespace App\Models;

use App\Models\User;
use App\Models\CustomerTemplateSection;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerContractTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContractDetailsNew extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(CustomerTemplateSection::class, 'section_id');
    }

    public function template()
    {
        return $this->belongsTo(CustomerContractTemplate::class, 'template_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
