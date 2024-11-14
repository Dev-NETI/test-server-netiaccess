<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcompany extends Model
{
    protected $table = 'tblcompany';
    protected $primaryKey = 'companyid';

    protected $fillable = [
        'company',
        'designation',
        'addressline1',
        'addressline2',
        'addressline3',
        'position',
        'busprice',
        'deletedid',
        'default_currency',
        'billing_statement_format',
        'lastpde_serialnumber',
        'billing_statement_template',
        'toggleBillingEmailNotification'
    ];

    use HasFactory;

    public function bank()
    {
        return $this->belongsTo(tblbillingaccount::class, 'defaultBank_id', 'billingaccountid');
    }

    public function email()
    {
        return $this->hasMany(CompanyEmail::class, 'company_id', 'companyid');
    }

    public function client_info()
    {
        return $this->hasMany(ClientInformation::class, 'company_id', 'companyid');
    }

    public function company()
    {
        return $this->belongsTo(tblpde::class, 'companyid', 'companyid');
    }
}
