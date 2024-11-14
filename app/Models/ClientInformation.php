<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientInformation extends Model
{

    protected $fillable = [
        'company_id',
        'client_information',
        'is_active',
        'label'
    ];
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'company_id', 'companyid');
    }

    public function selected_company_email()
    {
        return $this->hasMany(SelectedCompanyEmail::class, 'client_information_id', 'id');
    }
}
