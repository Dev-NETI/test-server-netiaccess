<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblbillingbilledlogs extends Model
{
    use HasFactory;
    protected $table = 'tblbillingbilledlogs';
    protected $fillable = ['enroledid', 'scheduleid', 'billingserialnumber', 'remarks', 'modifier'];

    public function enroled(){
        return $this->belongsTo(tblenroled::class, 'enroledid');
    }

    public function schedule(){
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }
}
