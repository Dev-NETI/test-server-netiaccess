<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmail extends Model
{
    use HasFactory;
    protected $fillable = ['email','company_id'];

    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'company_id', 'companyid');
    }

    public function selected_email()
    {
        return $this->hasOne(SelectedCompanyEmail::class, 'company_email_id', 'id');
    }
}
