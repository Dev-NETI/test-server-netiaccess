<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblbillingstatement extends Model
{
    use HasFactory;
    protected $table = 'tblbillingstatement';
    protected $fillable = ['companyid', 'scheduleid', 'enroledids', 'serial_number','original_name','billing_attachment_path', 'modified_by', 'deletedid'];
}
