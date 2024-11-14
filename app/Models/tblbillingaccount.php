<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblbillingaccount extends Model
{
    protected $table = 'tblbillingaccount';
    protected $primaryKey = 'billingaccountid';
    protected $fillable = [
        'billingaccount','accountname','accountnumber','bankname'
    ];
    use HasFactory;

    public function company()
    {
        return $this->hasMany(tblcompany::class, 'defaultBank_id', 'billingaccountid');
    }
}
