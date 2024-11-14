<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblbillingdrop extends Model
{
    protected $table = 'tblbillingdrop';
    protected $primarykey = 'dropid';
    
    public function enroled(){
        return $this->belongsTo(tblenroled::class, 'enroledid', 'enroledid');
    }

    use HasFactory;

}
