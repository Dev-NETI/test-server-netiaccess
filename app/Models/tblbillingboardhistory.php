<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class tblbillingboardhistory extends Model
{
    use HasFactory;
    protected $table = 'tblbillingboardhistory';
    protected $fillable = ['scheduleid', 'companyid', 'isunderbycompanyid', 'fromboard', 'toboard', 'serialnumber', 'modified_by'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $auth = Auth::user();
            $model->modified_by = $auth->f_name . ' ' . $auth->l_name;
        });
    }
}
