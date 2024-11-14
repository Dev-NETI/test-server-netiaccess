<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpde extends Model
{
    use HasFactory;
    protected $table = "tblpde";
    protected $primaryKey ="pdeID";
    protected $fillable=[
        'requestaccountdesignation',
        'requestby',
        'requestfleet',
        'pdestatusid',
        'surname',
        'givenname',
        'middlename',
        'suffix',
        'position',
        'vessel',
        'statusid',
        'imagepath',
        'attachmentpath',
        'companyid',
        'age',
        'dateofbirth',
        'passportno',
        'passportexpdate',
        'medicalexpdate',
        'rankid',
        'pdeserialno',
    'deletedid'];

    public function company(){

        return $this->belongsTo(tblcompany::class,'companyid','companyid');
    }

    public function rank(){

        return $this->belongsTo(tblrank::class,'rankid','rankid');
    }

    public function fleet()
    {
        return $this->belongsTo(tblfleet::class, 'requestfleet', 'fleetid');
    }


    
   
}
