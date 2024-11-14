<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpaymentmode extends Model
{
    protected $table = 'tblpaymentmode';
    protected $primaryKey = 'paymentmodeid';
    use HasFactory;
}
