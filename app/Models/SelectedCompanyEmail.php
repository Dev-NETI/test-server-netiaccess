<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedCompanyEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_information_id','company_email_id'
    ];

    public function client_information()
    {
        return $this->belongsTo(ClientInformation::class , 'client_information_id', 'id');
    }

    public function company_email()
    {
        return $this->hasOne(CompanyEmail::class, 'company_email_id', 'id');
    }
}
