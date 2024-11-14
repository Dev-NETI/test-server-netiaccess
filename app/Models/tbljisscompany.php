<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljisscompany extends Model
{
    use HasFactory;
    protected $table = 'tbljisscompany';
    protected $fillable = [
        'company',
        'recipientname',
        'recipientposition',
        'companyaddressline',
        'companyaddressline2'
    ];

    public function pricematrix(){
        return $this->belongsTo(tbljisspricematrix::class,'company');
    }

    public function billing(){
        return $this->belongsTo(tbljissbilling::class,'company');
    }

}
