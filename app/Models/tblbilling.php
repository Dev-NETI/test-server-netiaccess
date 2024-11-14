<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblbilling extends Model
{
    use HasFactory;
    protected $table = 'tblbilling';
    protected $fillable = ['scheduleid', 'batchno', 'enroledids', 'companyid', 'filepath', 'serialnumber', 'billingstatusid', 'generated_by'];

    public function scheduleinfo()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }

    public function companyinfo()
    {
        return $this->belongsTo(tblcompany::class, 'companyid');
    }
}
